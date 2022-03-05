import { sendAjaxRequest } from './ajax-request.js';



export function addReportSubmissonEvent(){
    const reportButtons = document.querySelectorAll(".submit-report-buttons");
    reportButtons.forEach(element => {
        element.addEventListener("click", function(){  
            let report_details = document.querySelector("#report_details"+this.value).value;
            sendAjaxRequest('POST', '/post/' + this.value + '/report', {"report_details" : report_details}, null); 
            sendAjaxRequest('GET', '/post/' + this.value + '/report/check', null, isReportedHandler);
        }); 
    });
}

function isReportedHandler(){
    if(this.response != ''){
        let response = JSON.parse(this.response);
        document.getElementById('openReportModal' + response.id_post).setAttribute("data-bs-target", "#AlreadyReportedModal");
    }
}


export function checkIfAlreadyReported(){
    const openReportModalBttns = document.querySelectorAll(".openReportModalBttns");
    openReportModalBttns.forEach(element => {
        sendAjaxRequest('GET', '/post/' + element.value + '/report/check', null, isReportedHandler); 
    });
}
