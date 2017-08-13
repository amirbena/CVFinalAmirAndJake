$(function(){

    var user_id = window.location.hash.substr(1); //Get the section after hash tag from URL, e.g. index.html#Arik return 'Arik'
    console.log("user: ", user_id);
    function get_user_data(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserData.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }

    function get_user_networks(user_id, network_name){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserSocialNetworks.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
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

    function get_user_education(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhostCVFinalAmirAndJake/php/get_user_data/getUserEducation.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }

    function get_user_experience(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserExperience.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }

    function get_user_pro(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserProSkills.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }
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
    function get_user_per(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserPerSkills.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }
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
    function get_user_hobbies(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserHobbies.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }
    function get_user_languages(user_id){
        var data = $.ajax({
            type: "GET",
            url: "http://localhost/CVFinalAmirAndJake/php/get_user_data/getUserLanguages.php?user_id=" + user_id,
            async: false
        }).responseText;
        data = JSON.parse(data);
        return data;
    }
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

    //get the social networks
    $.get('http://localhost/CVFinalAmirAndJake/php/getSocialNetworks.php', function(data) {
        console.log('Got the social networks', data); //We just print whatever we got from the server
        var html = '<section>';
        for (var i = 0; i < data.length; i++) {
            if(i == Math.ceil(data.length / 2)){
                html += "</section>";
                html += "<section>";
            }
            html += "<h5>";
            html += data[i].name;
            html += "<input type='text' name='" + data[i].name + "' placeholder='" + data[i].name + " link'>";
            html += "</h5>";
        }
        html += '</section>';// closing the table tytles

        $('#social_networks').append(html);
        if(user_id > 0){
            data = get_user_networks(user_id);
            console.log("user_networks: ",data);
            for (var i = 0; i < data.length; i++) {
                document.getElementsByName(getNetworkName(data[i].network_id))[0].value = data[i].value;
            }
        }
    });
    //get the pro skills
    $.get('http://localhost/CVFinalAmirAndJake/php/getProSkills.php', function(data) {
        console.log('Got the pro skills', data); //We just print whatever we got from the server
        var html = '<section>';
        html += '<h2>Pro skills</h2>';
        html += '<ul>';
        for (var i = 0; i < data.length; i++) {
            html += "<il>";
            html += data[i].name;
            html += "<input type='text' name='" + data[i].name + "' placeholder='1 - 100'>";
            html += "</il>";
        }
        html += '</ul>';// closing the table tytles
        html += "</section>";
        $('#skills').append(html);
        if(user_id > 0){
            data = get_user_pro(user_id);
            console.log("user_pro_skills: ",data);
            for (var i = 0; i < data.length; i++) {
                document.getElementsByName(getProSkillName(data[i].skill_id))[0].value = data[i].value;
            }
        }
    });

    //get the per skills
    $.get('http://localhost/CVFinalAmirAndJake/php/getPerSkills.php', function(data) {
        console.log('Got the per skills', data); //We just print whatever we got from the server
        var html = '<section>';
        html += '<h2>Per skills</h2>';
        html += '<ul>';
        for (var i = 0; i < data.length; i++) {
            html += "<il>";
            html += data[i].name;
            html += "<input type='text' name='" + data[i].name + "' placeholder='1 - 100'>";
            html += "</il>";
        }
        html += '</ul>';// closing the table tytles
        html += "</section>";
        $('#skills').append(html);
        if(user_id > 0){
            data = get_user_per(user_id);
            console.log("user_per_skills: ",data);
            for (var i = 0; i < data.length; i++) {
                document.getElementsByName(getPerSkillName(data[i].skill_id))[0].value = data[i].value;
            }
        }
    });

    //get the hobbies
    $.get('http://localhost/CVFinalAmirAndJake/php/getHobbies.php', function(data) {
        console.log('Got the hobbies', data); //We just print whatever we got from the server
        var html = '<section>';
        html += '<h2>Hobbies</h2>';
        html += '<table>';
        for (var i = 0; i < data.length; i++) {
            html += "<tr>";
            html += "<td>";
            html += "<input type='checkbox' name='" + data[i].name + "' value='" + data[i].name + "'><br>";
            html += "</td>";
            html += "<td>";
            var name = data[i].name.replace("_"," ");
            html += name;
            html += "</td>";
            html += "</tr>";
        }
        html += '</table>';
        html += "</section>";
        $('#hobbies').append(html);
        if(user_id > 0){
            data = get_user_hobbies(user_id);
            console.log("user_hobbies: ",data);
            for (var i = 0; i < data.length; i++) {
                document.getElementsByName(data[i].value)[0].checked = true;
            }
        }
    });

    //get the languages
    $.get('http://localhost/CVFinalAmirAndJake/php/getLanguages.php', function(data) {
        console.log('Got the languages', data); //We just print whatever we got from the server
        var html = '<section>';
        html += '<h2>Languages</h2>';
        html += '<ul>';
        for (var i = 0; i < data.length; i++) {
            html += "<li>";
            html += "<input type='text' name='" + data[i].name + "' placeholder='" + data[i].name + " (1 - 100)'>";
            html += "</li>";
        }
        html += '</ul>';
        html += "</section>";
        $('#languages').append(html);
        if(user_id > 0){
            data = get_user_languages(user_id);
            console.log("user_languages: ",data);
            for (var i = 0; i < data.length; i++) {
                document.getElementsByName(getLanguageName(data[i].language_id))[0].value = data[i].value;
            }
        }
    });
    if(user_id > 0){
        //  user data
        data = get_user_data(user_id);
        console.log("user_data", data);
        document.getElementsByName("first_name")[0].value = data.first_name;
        document.getElementsByName("last_name")[0].value = data.last_name;
        document.getElementsByName("degree")[0].value = data.degree;
        document.getElementsByName("phone")[0].value = data.phone;
        document.getElementsByName("address")[0].value = data.address;
        document.getElementsByName("email")[0].value = data.email;
        document.getElementsByName("about_me")[0].value = data.about_me;

        //  user experience
        data = get_user_experience(user_id);
        document.getElementsByName("exp_num")[0].value = data.length;
        console.log("user_experience", data);
        var html = "";
        for (var i = 0; i < data.length; i++) {
            html += "<section id='experience'>";
            html += "<section>";
            html += "<input type='text' name='exp_title_" + i + "' placeholder='Title'><br><br>";
            html += "<input type='text' name='exp_start_" + i + "' class='datepicker' placeholder='start date'><br><br>";
            html += "<input type='text' name='exp_end_" + i + "' class='datepicker' placeholder='end date'><br><br>";
            html += "</section>";
            html += "<section>";
            html += "<input type='text' name='exp_company_" + i + "' placeholder='Company name'><br><br>";
            html += "<textarea rows='4' cols='35' name='exp_description_" + i + "' placeholder='Description'></textarea><br><br>";
            html += "</section>";
            html += "</section>";
        }
        $('#experience_data').html(html);
        $( ".datepicker" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
        for (var i = 0; i < data.length; i++) {
            document.getElementsByName('exp_title_' + i)[0].value = data[i].title;
            document.getElementsByName('exp_start_' + i)[0].value = data[i].start_date;
            document.getElementsByName('exp_end_' + i)[0].value = data[i].end_date;
            document.getElementsByName('exp_company_' + i)[0].value = data[i].company;
            document.getElementsByName('exp_description_' + i)[0].value = data[i].description;
        }

        //  user education
        data = get_user_education(user_id);
        document.getElementsByName("edu_num")[0].value = data.length;
        console.log("user_education", data);
        var html = "";
        for (var i = 0; i < data.length; i++) {
            html += "<section id='edu'>";
            html += "<section>";
            html += "<input type='text' name='edu_title_" + i + "' placeholder='Title'><br><br>";
            html += "<input type='text' name='edu_start_" + i + "' class='datepicker' placeholder='start date'><br><br>";
            html += "<input type='text' name='edu_end_" + i + "' class='datepicker' placeholder='end date'><br><br>";
            html += "</section>";
            html += "<section>";
            html += "<input type='text' name='edu_place_" + i + "' placeholder='Place'><br><br>";
            html += "<textarea rows='4' cols='35' name='edu_description_" + i + "' placeholder='Description'></textarea><br><br>";
            html += "</section>";
            html += "</section>";
        }
        $('#edu_data').html(html);
        $( ".datepicker" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
        for (var i = 0; i < data.length; i++) {
            document.getElementsByName('edu_title_' + i)[0].value = data[i].title;
            document.getElementsByName('edu_start_' + i)[0].value = data[i].start_date;
            document.getElementsByName('edu_end_' + i)[0].value = data[i].end_date;
            document.getElementsByName('edu_place_' + i)[0].value = data[i].location;
            document.getElementsByName('edu_description_' + i)[0].value = data[i].description;
        }
        $(window).scrollTop(0);
        //window.scrollTo(0, document.body.scrollHeight);
    }
});
