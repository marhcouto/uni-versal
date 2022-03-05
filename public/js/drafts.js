import { sendAjaxRequest } from './ajax-request.js';


// Drafts ajax

function getDraftsHandler(){
    let json = JSON.parse(this.responseText);
    let goalDiv = document.querySelector('.drafts-list');
    goalDiv.innerHTML = json.response;
}

export function getDrafts(){
    sendAjaxRequest('GET', '/user/drafts', null, getDraftsHandler);
}



