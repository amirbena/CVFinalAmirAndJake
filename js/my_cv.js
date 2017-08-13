$(function(){

    console.log("sending request");
    var user_id = window.location.hash.substr(1); //Get the section after hash tag from URL, e.g. index.html#Arik return 'Arik'
    console.log("user-> ", user_id);
    if(user_id === '') {
        alert('No user id selected');
    } else {
          $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserData.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

              console.log('user data:', data); //We just print whatever we got from the server
              //Basic info
              $('#intro header h1').text(data.first_name); //Let's use the data from server and pub it on page!
              $('#intro header h2').text(data.last_name);
              $('#intro header h3').text(data.degree);
              $('#phone').text(data.phone);
              $('#area').text(data.address);
              $('#email').text(data.email);
              $('#intro article p').text(data.about_me);


        });

        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserSocialNetworks.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('social-networks:', data); 

            var html = "";
            for (var i = 0; i < data.length; i++) {
                html += "<li>";
                html += "<i class='" + getNetworkIcon(data[i].network_id) + "' aria-hidden='true'></i>";
                html += "<a href='" + data[i].value + "'>" + getNetworkName(data[i].network_id) + "</a>";
                html += "</li>";
            }
            $("#social-networks").html(html);
        });

        function getNetworkIcon(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getSocialNetworks.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].icon_name;
                }
            }
            return null;
        }
        function getNetworkName(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getSocialNetworks.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].name;
                }
            }
            return null;
        }

        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserExperience.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('experience:', data); //We just print whatever we got from the server
            var monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
                ];

            var html = "<h2 class='secondary_tytle'>experience</h2>";
            for (var i = 0; i < data.length; i++) {
                html += "<section>";
                html += "<aside>";
                html += "<h5>" + data[i].title + "</h5>";
                var start_date = new Date(data[i].start_date);
                var end_date = new Date(data[i].end_date);
                html += "<span>";
                html += monthNames[start_date.getMonth()] + " " + start_date.getFullYear();
                html += " - " + monthNames[end_date.getMonth()] + " " + end_date.getFullYear();
                html += "</span>";
                html += "</aside>";
                html += "<article>";
                html += "<h4>" + data[i].company + "</h4>";
                html += "<p>";
                html += data[i].description;
                html += "</p>";
                html += "</article>";
                html += "</section>";
            }
            $("#experience").html(html);
        });

        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserEducation.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('education:', data); //We just print whatever we got from the server
            var monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
                ];

            var html = "<h2 class='secondary_tytle'>education</h2>";
            for (var i = 0; i < data.length; i++) {
                var start_date = new Date(data[i].start_date);
                var end_date = new Date(data[i].end_date);
                html += "<section>";
                html += "<aside>";
                html += "<h5>" + data[i].title + "</h5>";
                html += "<span>";
                html += monthNames[start_date.getMonth()] + " " + start_date.getFullYear();
                html += " - " + monthNames[end_date.getMonth()] + " " + end_date.getFullYear();
                html += "</span>";
                html += "</aside>";
                html += "<article>";
                html += "<h4>" + data[i].location + "</h4>";
                html += "<p>";
                html += data[i].description;
                html += "</p>";
                html += "</article>";
                html += "</section>";
            }
            $("#education").html(html);
        });
        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserProSkills.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('pro skills:', data); //We just print whatever we got from the server

            var html = "<h2 class='secondary_tytle'>Pro Skills</h2>";
            html += "<article>";
            html += "<table class='table_list'>";
            for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>";
                html += getProSkillName(data[i].skill_id);
                html += "</td>";
                html += "<td>";
                html += "<progress max=100 value=" + data[i].value + "></progress>";
                html += "</td>";
                html += "</tr>";
            }
            html += "</table>";
            $("#pro_skills").html(html);
        });

        function getProSkillName(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getProSkills.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].name;
                }
            }
            return null;
        }
        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserPerSkills.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('pro skills:', data); //We just print whatever we got from the server

            var html = "<h2 class='secondary_tytle'>Per Skills</h2>";
            html += "<article>";
            html += "<table class='table_list'>";
            for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>";
                html += getPerSkillName(data[i].skill_id);
                html += "</td>";
                html += "<td>";
                html += "<progress max=100 value=" + data[i].value + "></progress>";
                html += "</td>";
                html += "</tr>";
            }
            html += "</table>";
            $("#per_skills").html(html);
        });

        function getPerSkillName(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getPerSkills.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].name;
                }
            }
            return null;
        }
        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserHobbies.php?user_id=' + user_id, function(data) { //This code makes an HTTP request

            console.log('hobbies:', data); //We just print whatever we got from the server

            var html = "<h2 class='secondary_tytle'>Hobbies</h2>";
            html += "<table class='table_list'>";
            html += "<tr>";
            for (var i = 0; i < data.length; i++) {
                html += "<td>";
                html += "<section>";
                html += "<i class='" + getHobbieClass(data[i].hobby_id) + "'></i>";
                html += "<br>";
                html += "<a href='#'>" + data[i].value + "</a>";
                html += "</section>";
                html += "</td>";
            }
            html += "</tr>";
            html += "</table>";
            $("#hobbies").html(html);
        });

        function getHobbieClass(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getHobbies.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].icon_name;
                }
            }
            return null;
        }
        $.get('http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserLanguages.php?user_id=' + user_id, function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

            console.log('languages:', data); //We just print whatever we got from the server

            var html = "<h2 class='secondary_tytle'>Languages</h2>";
            html += "<section>";
            html += "<table class='table_list'>";
            for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>";
                html += getLanguageName(data[i].language_id);
                html += "</td>";
                html += "<td>";
                html += "<progress max=100 value=" + data[i].value + "></progress>";
                html += "</td>";
                html += "</tr>";
            }
            html += "</table>";
            $("#languages").html(html);
        });

        function getLanguageName(id){
            var msg = $.ajax({
                type: "GET",
                url: "http://localhost/CVFinalAmirAndJake/php/getLanguages.php",
                async: false
            }).responseText;
            msg = JSON.parse(msg);
            for (var i = 0; i < msg.length; i++) {
                if(msg[i].id == id){
                    return msg[i].name;
                }
            }
            return null;
        }
    }// else
});
