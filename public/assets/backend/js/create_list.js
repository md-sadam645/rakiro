$(document).ready(function()
{
    // $("#city").attr("disabled","disabled");
    // $("#state").attr("disabled","disabled");
    $("#pincode").attr("disabled","disabled");

     // Start select country 
     $("#country").on("change",function()
     {
        var countryId = this.value;
        if(countryId == "IN")
        {
            $("#city").attr("required","required");
            $("#state").attr("required","required");
            $("#pincode").attr("required","required");

            $("#pincode").removeAttr("disabled");

            $(".filter-btn").attr("disabled","disabled");
            $("#pincode").on("input",function()
            {
                var pincode = this.value;
                if(pincode.length > 6 || pincode.length < 6)
                {
                    $(".pincode-error").removeClass("d-none");
                }
                else
                {
                    $(".pincode-error").addClass("d-none");
                }
                
                if(pincode.length == 6)
                {
                    $.ajax({
                        type: "GET",
                        url: "/pincode/"+pincode,
                        success:function(response)
                        {
                            console.log(response);
                            if(response != "")
                            {
                                $("#city").val(response.u_city);
                                $("#state").val(response.u_state);
                                $(".filter-btn").removeAttr("disabled");
                            } 
                            else
                            {
                                $(".filter-btn").attr("disabled","disabled");
                            }
                        },
                        error : function(xhr,error,response)
                        {
                            // console.log(response);
                        }
                    });
                }
                else
                {
                    $("#city").val("");
                    $("#state").val("");
                    $(".filter-btn").attr("disabled","disabled");
                }
            });
        }
        else
        {
            $("#city").val("");
            $("#state").val("");
            $("#city").removeAttr("required");
            $("#state").removeAttr("required");
            $("#pincode").attr("disabled","disabled");
            $("#pincode").val("");
            $(".pincode-error").addClass("d-none");
            $(".filter-btn").removeAttr("disabled");
        }
    });
    // End select country


    // Start select state 
    // $("#state").on("change",function(){
    //     var stateId = this.value;

    //     $("#city").html("");
    //     var city = document.querySelector("#city");
    //     var i;

    //     if(stateId != "choose state")
    //     {
    //         $.ajax({
    //             type: "GET",
    //             url: "/city/"+stateId.split(",")[0],
    //             success:function(response)
    //             {
    //                 console.log(response);
    //                 var empty_option = document.createElement("OPTION");
    //                 empty_option.innerHTML = "Choose City";
    //                 empty_option.value = "choose city";

    //                 if(response.length != 0)
    //                 {   
    //                     city.append(empty_option);

    //                     for(i=0; i<response.length; i++)
    //                     {
    //                         var option = document.createElement("OPTION");
    //                         option.innerHTML = response[i].name;
    //                         option.value = response[i].name;

    //                         city.append(option);
    //                     }
    //                 } 
    //                 else
    //                 {
    //                     city.append(empty_option);
    //                 }  
    //             },
    //             error : function(xhr,error,response)
    //             {
    //                 console.log(response);
    //             }
    //         });
    //     }
    //     else
    //     {
    //         var option = document.createElement("OPTION");
    //         option.innerHTML = "Choose City";
    //         option.value = "choose city";

    //         city.append(option);
            
    //     }
    // });
    // End select state

    // Start City state 
    // $("#city").on("change",function(){
    //     var cityName = this.value;
    //     $("#pincode").html("");
    //     var pincode = document.querySelector("#pincode");
    //     var i;
 
    //     if(cityName != "choose pincode")
    //     {
    //         $.ajax({
    //             type: "GET",
    //             url: "/pincode/"+cityName,
    //             success:function(response)
    //             {
    //                 var empty_option = document.createElement("OPTION");
    //                 empty_option.innerHTML = "Choose Pincode";
    //                 empty_option.value = "choose pincode";

    //                 if(response.length != 0)
    //                 {
    //                     pincode.append(empty_option);

    //                     for(i=0; i<response.length; i++)
    //                     {
    //                         var option = document.createElement("OPTION");
    //                         option.innerHTML = response[i].code;
    //                         option.value = response[i].code;

    //                         pincode.append(option);
    //                     }
    //                 }
    //                 else
    //                 {
    //                     pincode.append(empty_option);
    //                 }
    //             },
    //             error : function(xhr,error,response)
    //             {
    //                 console.log(response);
    //             }
    //         });
    //     }
    //     else
    //     {
    //         var option = document.createElement("OPTION");
    //         option.innerHTML = "Choose Pincode";
    //         option.value = "choose pincode";

    //         pincode.append(option);
            
    //     }
    // });
    // End pincode state 
});