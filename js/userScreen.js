$(function(){
    /*
    This section gets all the users from the server and lists them in client
    */
    console.log("sending request to get all users data");

    $.get('http://localhost/CVFinalAmirAndJake/php/getAllUsersData.php', function(data) { //This code makes an HTTP request to /arik and puts the data in the 'data' variable

          console.log('Got data', data); //We just print whatever we got from the server
          var html = '';
          html += "<table id='line'>";// start of the table
          html += "<tr id='line'>";
          html += '<td><b>First Name</b></td>';
          html += '<td><b>Last Name</b></td>';
          html += '<td><b>Email</b></td>';
          html += '<td><b>Delete</b></td>';
          html += '<td><b>Edit</b></td>';
          html += '</tr>';// closing the table tytles

          for (index in data){
               // adding user line
              html += "<tr id='line'>";
              html += "<td><a href='http://localhost/CVFinalAmirAndJake/index.html#" + data[index].id + "'>" + data[index].first_name + '</a></td>';
              html += '<td>'+ data[index].last_name +'</td>';
              html += '<td>'+ data[index].email +'</td>';
              html += '<td>';
              html += "<input type='button' name='" + data[index].id + "' value='Delete' onclick='deleteUser(this.name)'>" +'</td>';
              html += '<td>';
              html += "<input type='button' name='" + data[index].id + "' value='Edit' onclick='editUser(this.name)'>";
              html += '</td>';
              html += '</tr>';
              // ending of the user line
          }
          html += '</table>';//closing the table

          $('#screen').append(html);
      });
});
