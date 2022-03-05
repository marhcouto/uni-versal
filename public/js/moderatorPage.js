import { sendAjaxRequest } from './ajax-request.js';

function seeReportsHandler(){
    let json = JSON.parse(this.responseText);
    let goalDiv = document.querySelector('.report-list');
    goalDiv.innerHTML = json.response;
}


function numReportsHandler(){
    let json = JSON.parse(this.responseText);

    let count = Object.keys(json).length;

    let goalDiv = document.getElementById("numberOfReports" + json[0].id_post);
    goalDiv.innerHTML = count;
}


function AddSeeAllReportsEvent(){
    const reportButtons = document.querySelectorAll(".openReportsModalBttn");
        reportButtons.forEach(element => {
            sendAjaxRequest('GET', '/moderator/reports/' + element.value + '/numReports', null, numReportsHandler);

            element.addEventListener("click", function(){  
                sendAjaxRequest('GET', '/moderator/reports/' + this.value + '/reports', null, seeReportsHandler);
            }); 
        });
}

AddSeeAllReportsEvent();