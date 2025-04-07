$(document).ready(function()
{
    $("#show-pass").click(function(){
        var attrValue = $(".pass-field").attr("type");
        if(attrValue == "password")
        {
            $(".pass-field").attr("type","text");
            $(this).removeClass("fa-eye-slash");
            $(this).addClass("fa-eye");
        }
        else
        {
            $(".pass-field").attr("type","password");
            $(this).removeClass("fa-eye");
            $(this).addClass("fa-eye-slash");
        }
    });

    $("#show-pass2").click(function(){
        var attrValue = $(".pass-field2").attr("type");
        if(attrValue == "password")
        {
            $(".pass-field2").attr("type","text");
            $(this).removeClass("fa-eye-slash");
            $(this).addClass("fa-eye");
        }
        else
        {
            $(".pass-field2").attr("type","password");
            $(this).removeClass("fa-eye");
            $(this).addClass("fa-eye-slash");
        }
    });

    $(".pass-new-confirm").on("input",function(){
        $passNew = $(".pass-new").val();
        if($passNew == this.value)
        {
            $(".confirm-warning").addClass("d-none");
            $(".change-pass-btn").removeAttr("disabled");
        }
        else
        {
            $(".confirm-warning").removeClass("d-none");
            $(".change-pass-btn").attr("disabled","disabled");
        }
    })

    $(".pass-new").on("input",function(){
        $passNew = $(".pass-new-confirm").val();
        if($passNew == this.value)
        {
            $(".confirm-warning").addClass("d-none");
            $(".change-pass-btn").removeAttr("disabled");
        }
        else
        {
            $(".confirm-warning").removeClass("d-none");
            $(".change-pass-btn").attr("disabled","disabled");
        }
    })
});