const my = {
    "getDate": function(){
        var d = new Date(Date.now());
        return d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear();
    },
    "initFormExtendedDatetimepickers": function() {
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
    },
}