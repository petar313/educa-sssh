<?php
// src/AppBundle/Controller/UserController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Form\Model\Registration;
use AppBundle\Form\Type\RegistrationType;
use AppBundle\Form\Type\ResetPasswordType;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\Type\ChangePasswordType;
use AppBundle\Form\Type\ProfileEditType;


/**
 * 
 */
class UserController extends Controller
{
    
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(RegistrationType::class, $registration, array('action' => $this->generateUrl('user_create')));

        return $this->render(
            'AppBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * @Route("/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $session = $this->get('session');
        if ($session->has('_locale')) {
            $locale= $session->get('_locale');
        }
        else
        {
            $locale='en';
        }
        
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(RegistrationType::class, new Registration());

        $form->handleRequest($request);

        if ($form->isValid() and $form->isSubmitted()) {
            $registration = $form->getData();
            
            $user=$registration->getUser();
                        
            $password=uniqid();//the password will be shown after activation
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            
            $user->setActivationhash();
            
            $user->setRole($em->getRepository('AppBundle:Role')->find(1));
            
            //$user->setPerson(NULL);//user is personless at first
            $person=$user->getPerson();
            $em->persist($person);
            $em->persist($user);
            $em->flush();
            
            $this->sendConfirmationEMail($user);//send confirmation e-mail
            
            $this->addFlash(
            'notice',
            $this->get("translator")->trans("mail.notice") . $this->get("translator")->trans("mail.notice2") 
                    
        );

            return $this->redirectToRoute("check_permission");
        }

        return $this->render(
            'AppBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    
    /**
     * @Route("/activation", name="activation")
     * @Method({"GET"})
     */
    public function activateAction(Request $request)
    {
        $email = $request->get('email');
        $invitationCode   = $request->get('code');

        try
        {
            $user=$this->verify($email, $invitationCode);
        }
        catch (\Exception $e)
        {
            $this->addFlash('error', "Invalid");
            return $this->redirectToRoute('check_permission');
        }
        
        $user->setIsActive(TRUE);
        $user->setActivationhash();
        
        $this->addFlash(
            'notice',
            $this->get("translator")->trans("mail.notice3")
        );
        
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute("check_permission");
    }
    
    
     /**
     * @Route("/login", name="user_login")
     */
    public function loginAction(Request $request)
    {
        
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        //reset password form
        $form = $this->createForm(ResetPasswordType::class);

        return $this->render(
            'AppBundle:User:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'reset_form'    => $form->createView(),
            )
        );
        
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }  
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
    
    /**
     * Change password
     * 
     * @Route("/changepasswd", name="change_passwd")
     */
    public function changePasswdAction(Request $request)
    {
      $changePasswordModel = new ChangePassword();
      $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          
        $newPwd=$changePasswordModel->getNewPassword();
            
        $user=$this->doEncodePassword($this->getUser(),$newPwd);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
            
          $this->addFlash(
            'notice',
            $this->get("translator")->trans("mail.notice4")
        );
 
      }

      return $this->render('AppBundle:User:changePasswd.html.twig', array(
          'form' => $form->createView(),
      ));      
    }

    
    /**
     * Checking user and sending mail for reset password
     * 
     * @Route("/resetpwd", name="reset_password_request")
     */
    public function resetRequestAction(Request $request) {
        
      $form = $this->createForm(ResetPasswordType::class);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
                    
          $email=$form->getData('email');
          $login=$form->getData('login');
          
          try
            {
                $em    = $this->getDoctrine()->getManager();
                $repo=$em->getRepository('AppBundle:User');
                $users = $repo->findBy(['login' => $login, 'email'=>$email]);
                
                if (count($users) == 0)
                {
                  throw new \Exception($this->get("translator")->trans("forms.exception1"));
                }
                
                $user = $users[0];
          
                $user->setActivationhash();
                
                $user->setUpdated(new \DateTime("Now"));

                //$user->setPassword($hash);//no need for this

                $em->persist($user);
                $em->flush();

                $this->sendResetPasswordEMail($user);

                $this->addFlash('notice', $this->get("translator")->trans("mail.notice5"));
            }
            catch (\Exception $e)
            {
                $this->addFlash('error', $e->getMessage());
                
            }
          
      }
        
      return $this->redirectToRoute("check_permission");
        
    }
    
    
    /**
     * After user clicke reset password link in e-mail
     * 
     * @Route("/reset", name="reset_password")
     * 
     */
    public function resetPasswordFormAction(Request $request)
    {
        $email = $request->get('email');
        $invitationCode   = $request->get('code');

        try
        {
            $user=$this->verify($email, $invitationCode);
        }
        catch (\Exception $e)
        {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('error');
        }
        
        $registration = new ChangePassword();
        
        //$registration->setUser($user);
        
        $form  = $this->createForm(ChangePasswordType::class, 
                        $registration, 
                        ['action' => $this->generateUrl(
                                'reset_password',
                                array('email'=>$email, 
                                      'code'=>$invitationCode)), 
                         'method' => 'POST']);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            
            $registration = $form->getData();
            
            $user=$this->doEncodePassword($user,$registration->getNewPassword());
            
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("check_permission");
        }

        return $this->render('AppBundle:User:register.html.twig', ['form' => $form->createView(), 'email' => $email]);
    }   
      
    /**
     * @Route("/profile", name="profile_show")
     */
    public function showProfileAction()
    {
        $user = $this->getUser();
        
        
        return $this->render(
            'AppBundle:User:profileShow.html.twig',
            array('user' => $user)
        );
    }
    
    
    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editProfileAction()
    {
        $user = $this->getUser();
        
        $form = $this->createForm(ProfileEditType::class, $user, array(
            'action' => $this->generateUrl('profile_update'),
        ));
        
        return $this->render(
            'AppBundle:User:profileEdit.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * @Route("/profile/update", name="profile_update")
     * @Method("POST")
     */
    public function updateProfileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();

        $form = $this->createForm(ProfileEditType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isValid() and $form->isSubmitted()) {
            
            $user->uploadPicture();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("check_permission");
        }

        return $this->render(
            'AppBundle:User:profileEdit.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * Send confirmation e-mail
     * 
     * @param \User $user
     */
    private function sendConfirmationEMail($user)
    {
        $email=$user->getEmail();
        
        $mailer = $this->get('mailer');

        $message = $mailer->createMessage()
                ->setSubject($this->get("translator")->trans("mail.subject1"))
                ->setFrom('educa.sssh@gmail.com')
                ->setTo($email)
                ->setBody(
                $this->renderView('AppBundle:Email:confirmation.html.twig', ['user' => $user]), 'text/html'
        );

        $mailer->send($message);
    }
    
    
     /**
     * sends reset password email
      * 
     * @param \User $user
     */
    private function sendResetPasswordEMail($user)
    {
        $email=$user->getEmail();
        
        $mailer = $this->get('mailer');

        $message = $mailer->createMessage()
                ->setSubject($this->get("translator")->trans("mail.subject2"))
                ->setFrom('educa.sssh@gmail.com')
                ->setTo($email)
                ->setBody(
                $this->renderView('AppBundle:Email:resetPassword.html.twig', ['user' => $user]), 'text/html'
        );

        $mailer->send($message);
    }
    
    
    
    /**
     * Verify activation/reset password link
     * 
     * @param string $email
     * @param string $code
     * @return \User
     */
    private function verify($email, $code)
    {
        $repo    = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $users = $repo->findBy(['activationhash' => $code, 'email'=>$email]);

        if (count($users) != 1)//only one user
        {
            throw new \Exception();
        }
        $user = $users[0];

        $exp = $user->getUpdated();
        $activationExpireTime=$this->container->getParameter('activationExpireTime');
        $exp -> modify('+'.$activationExpireTime.' day');//access config parameter !!!
        $now = new \DateTime();
        if ($exp < $now)
        {
            $this->addFlash(
                'error',
                $this->get("translator")->trans("forms.exception2")          
                );
            return $this->redirectToRoute("error");
        }

        if ($user->getActivationhash() !== $code)
        {
            $this->addFlash(
                'error',
                $this->get("translator")->trans("forms.exception3")          
                );
            return $this->redirectToRoute("error");
        }
        
        return $user;
    }
  
        
    /**
     * 
     * @param \User $user
     * @param \string $newPwd
     * @return \User
     */
    private function doEncodePassword($user,$newPwd)
    {
        $encoder = $this->container->get('security.password_encoder');
        $new_pwd_encoded = $encoder->encodePassword($user, $newPwd);
            
        $user->setPassword($new_pwd_encoded);
        $user->setActivationhash();
        
        return $user;
    }
    
}
