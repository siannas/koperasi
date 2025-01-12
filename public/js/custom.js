const my = {
    "getDate": function(){
        var d = new Date(Date.now());
        return d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear();
    },
    "inputToRupiah": function (){    
      var curval= this.value.replace(/Rp|,/g, "");
      if (/^[\d.]*$/.test(curval) && curval.trim()!=='') {   //is it valid float number?
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
        this.value = this.oldValue=  'Rp'+parseFloat(curval)
          .toFixed(2)
          .toString()
          .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      } else if (this.hasOwnProperty("oldValue")) {      
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    },
    "formatRupiah": function(angka, prefix){
      angka = angka.toString();
      var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split   		= number_string.split(','),
      sisa     		= split[0].length % 3,
      rupiah     		= split[0].substr(0, sisa),
      ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return 'Rp' + rupiah ;
    },
    "initFormExtendedDatetimepickers": function(year = null) {
        if (year == null) {
          year = new Date().getFullYear();
        }
        $('.datetimepicker').datetimepicker({
          icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          }
        });
    
        $('.datepicker').datetimepicker({
          format: 'DD/MM/YYYY',
          icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          }
        });
    
        $('.timepicker').datetimepicker({
          //          format: 'H:mm',    // use this format if you want the 24hours timepicker
          format: 'h:mm A', //use this format if you want the 12hours timpiecker with AM/PM toggle
          icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
    
          }
        });

        $(".monthyearpicker").each(function(i, elem) {
          defaultVal = year + '-01-01';
          if (elem.value != "") {
            val = moment(elem.value, "MM/YYYY")
            defaultVal = val.format("YYYY-MM-DD")
          }
          elem.value = "";
          $(elem).datetimepicker( {
            viewMode: 'months',
            format: "MM/YYYY",
            icons: {
              time: "fa fa-clock-o",
              date: "fa fa-calendar",
              up: "fa fa-chevron-up",
              down: "fa fa-chevron-down",
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove',
            },
            useCurrent: false,
            defaultDate: defaultVal,
            minDate: new Date(year + "-01-01T00:00:00"),
            maxDate: new Date(year + "-12-31T23:59:59"),
          });
        });

        $(".yearpicker").datetimepicker( {
          viewMode: 'years',
          format: 'YYYY'
        });

        $(".monthpicker").each(function(i, elem) {
          defaultVal = year + '-01-01';
          if (elem.value != "") {
            val = moment(elem.value + " " + year, "MMMM YYYY")
            defaultVal = val.format("YYYY-MM-DD")
          }
          elem.value = "";
          $(elem).datetimepicker( {
            viewMode: 'months',
            format: 'MMMM',
            useCurrent: false,
            defaultDate: defaultVal,
            minDate: new Date(year + "-01-01T00:00:00"),
            maxDate: new Date(year + "-12-31T23:59:59"),
          });
        });
    },
}