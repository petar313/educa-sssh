 /**
 * Bootstrap Table Croatian translation
 * Author: Petar Jakovljević (petar.jakovljevic.com)
 * Author: Petar Jakovljević (petar.jak0vljevic.com)
 */
(function ($) {
    'use strict';

    $.fn.bootstrapTable.locales['sr-SR'] = {
        formatLoadingMessage: function () {
            return 'Molimo pričekajte ...';
        },
        formatRecordsPerPage: function (pageNumber) {
            return pageNumber + ' broj zapisa po stranici';
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return 'Prikazujem ' + pageFrom + '. - ' + pageTo + '. od ukupnog broja zapisa ' + totalRows;
        },
        formatSearch: function () {
            return 'Pretraži';
        },
        formatNoMatches: function () {
            return 'Nije pronađen ni jedan zapis';
        },
        formatPaginationSwitch: function () {
            return 'Prikaži/sakri stranice';
        },
        formatRefresh: function () {
            return 'Osvježi';
        },
        formatToggle: function () {
            return 'Promijeni prikaz';
        },
        formatColumns: function () {
            return 'Kolone';
        },
        formatAllRows: function () {
            return 'Sve';
        }
    };

    $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales['sr-SR']);

})(jQuery);
