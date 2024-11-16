jQuery(document).ready(function() {
    var res_point = jQuery('.responsiveTable').attr('responsive-point')
    if (jQuery(window).width() <= parseInt(res_point)) {
        var table = jQuery('.responsiveTable .table');
        var tableTh = document.querySelectorAll('.responsiveTable .table th');
        var tableTr = document.querySelectorAll('.responsiveTable tbody tr');
        var th_Array = [];
        var tr_Array = [];
        var td_Array = [];
        var tableTd;
        var workStatus = false;

        tableTh.forEach(thItem => {
            let th_txt = jQuery(thItem).html();
            th_Array.push(th_txt);
        });

        tableTr.forEach(trItem => {
            let tr_txt = jQuery(trItem).html();
            var temp_array = [];
            jQuery(trItem).children('td').each(function (i, element) {
                let td_txt = jQuery(element).html();
                temp_array.push(td_txt)
            });
            // tableTd.forEach(tdItem => {
            //     let td_txt = jQuery(tdItem).html();
            //     temp_array.push(td_txt)
            // });
            td_Array.push({ ...temp_array })
            tr_Array.push(tr_txt);
        });

        jQuery('.responsiveTable').append(`<div class="table-wrapper"></div>`);

        tr_Array.forEach((trValue, trIndex) => {
            let itemTr = ``;
            itemTr += `<div class="table-data-item">`;
            th_Array.forEach((thValue, thIndex) => {
                itemTr += `<div class="table-tr">`;
                itemTr += `<div class="table-th">${thValue}</div>`;

                td_Array.forEach((tdValue, tdIndex) => {

                    if (trIndex == tdIndex) {
                        $.each(tdValue, function (tdInsideIndex, tdInsideVal) {
                            if (thIndex == tdInsideIndex) {
                                itemTr += `<div class="table-td">${tdInsideVal}</div>`;
                            }
                        });
                    }
                });
                itemTr += `</div>`;
            });
            itemTr += `</div>`;
            jQuery('.table-wrapper').append(itemTr);
            if (trIndex == (tr_Array.length - 1)) {
                console.log('akshdjkhsjkhdjhsdkld');
                workStatus = true;
                checkWorkStatus();
            }
        });

        function checkWorkStatus() {
            if (workStatus) {
                jQuery('.responsiveTable .table').remove();
            }
        }
    }   

})