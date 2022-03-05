import { sendAjaxRequest } from './ajax-request.js';


// Notifications ajax

function checkUnseenNotifHandler(){
    let response = JSON.parse(this.response);
    if(response != ''){
        document.getElementById('notif-alert-icon').style.display = "flex";
    }
    else{
        document.getElementById('notif-alert-icon').style.display = "none";
    }
}

export function checkUnseenNotif(){
    sendAjaxRequest('GET','/notification/checkUnseen', null, checkUnseenNotifHandler);
}

function getNotificationsHandler(){
    let json = JSON.parse(this.responseText);
    let goalDiv = document.querySelector('.notif-list');
    goalDiv.innerHTML = json.response;
    updateNotifications();
}


export function getNotifications(){
    sendAjaxRequest('GET', '/notification', null, getNotificationsHandler);
}


function updateNotifications(){
    sendAjaxRequest('POST', '/notification/update', null, checkUnseenNotif);
}

export function deleteNotifications(){
    sendAjaxRequest('POST', '/notification/delete', null, getNotifications);
}


