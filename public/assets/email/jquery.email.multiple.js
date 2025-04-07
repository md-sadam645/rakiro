/**
 * Created by Malal91 and Haziel
 * Select multiple email by jquery.email_multiple
 * **/
$(document).ready(function(){
    $("#attachment-btn").click(function(){
        $("#attach-file").click();
    });
});

(function($){

    $.fn.email_multiple = function(options) {

        let defaults = {
            reset: false,
            fill: false,
            data: null
        };

        let settings = $.extend(defaults, options);
        let email = "";
        // let getValue = "";
        var allEmail = [];
        return this.each(function()
        {
            $(this).after("<label class=\"form-label mb-0 me-3\">Cc :</label>\n" +
                "<div class=\"all-mail\" name=\"email_cc\"></div>\n" +
                "<input type=\"text\" class=\"enter-mail-id\" placeholder=\"Enter Email\" />");
            let $orig = $(this);
            let $element = $('.enter-mail-id');
            
            //Start schedule email edited data appending here
            var edit_ccCons = $(".edit-ccEmails");
            var i;
            if(edit_ccCons.length > 0)
            {
                for(i=0; i<edit_ccCons.length; i++)
                {
                    $('.all-mail').append('<span class="email-ids">' + edit_ccCons[i].innerHTML + '<span class="cancel-email">x</span></span>');
                    // $element.val('');
                    // getValue = edit_ccCons[i].innerHTML;
                    allEmail += edit_ccCons[i].innerHTML + ",";
                    email += edit_ccCons[i].innerHTML + ';'
                    // console.log(i);

                    $("#email_cc").val(allEmail);
                }
            }
            //End schedule email edited data appending here
            // console.log(getValue);
            $element.keydown(function (e) 
            {
                $element.css('border', '');
                if (e.keyCode === 39 || e.keyCode === 188) 
                {
                    let getValue = $element.val();
                 
                    if (/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(getValue))
                    {
                        $('.all-mail').append('<span class="email-ids">' + getValue + '<span class="cancel-email">x</span></span>');
                       
                        $element.val('');
                        allEmail += getValue + ",";
                        email += getValue + ';'
                        // console.log(allEmail);
                        $("#email_cc").val(allEmail);
                    } 
                    else
                    {
                        $element.css('border', '1px solid red')
                    }
                }

                $orig.val(email.slice(0, -1))
            });

            $(document).on('click','.cancel-email',function()
            {   
                var emailP = this.parentElement;
                var parentEData = emailP.innerHTML;
                var splitData = parentEData.split("<span")[0];
                // console.log(splitData);

                var allStoredEmail = allEmail.split(",");
              
                var emailIndex = allStoredEmail.findIndex(checkEmail);
                function checkEmail(email) 
                {
                    return email == splitData;
                }

                delete allStoredEmail[emailIndex];

                allEmail = allStoredEmail.toString();
                $("#email_cc").val(allEmail);
                
                $(this).parent().remove();
               
            });

            if(settings.data){
                $.each(settings.data, function (x, y) 
                {
                    if (/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(y)){
                        $('.all-mail').append('<span class="email-ids">' + y + '<span class="cancel-email">x</span></span>');
                        $element.val('');

                        email += y + ';'
                    } else {
                        $element.css('border', '1px solid red')
                    }
                })

                $orig.val(email.slice(0, -1))
            }

            if(settings.reset){
                $('.email-ids').remove()
            }

            return $orig.hide()
        });
    };

})(jQuery);
