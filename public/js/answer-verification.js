import { sendAjaxRequest } from './ajax-request.js';

// Upvotes Ajax
function verify(id_post) {
    sendAjaxRequest('POST','/answer/verify', {id_post: id_post}, verifyHandler);
}
  
function verifyHandler() {
    let response = JSON.parse(this.response);
    checkVerified(response);
}

function checkVerified(id_post) {
    sendAjaxRequest('GET','/answer/' + id_post, null, checkVerifiedHandler);
}

function checkVerifiedHandler() {
    let response = JSON.parse(this.response);
    let verified_button = document.getElementById(response.id_post + '-verify-button');

    if (response.verified) verified_button.style = "background: #134A91;";
    else verified_button.style = "background: #287BE5 ;";
}

function checkSolved(id_post) {
    sendAjaxRequest('GET','/question/' + id_post, null, checkSolvedHandler);
}

function checkSolvedHandler() {
    let response = JSON.parse(this.response);
    if (response.solved) {
        document.getElementById(response.id_post + '-solved-icon').style = "visibility: visible;";
    } else {
        document.getElementById(response.id_post + '-solved-icon').style = "visibility: hidden;";
    }
}

export function onAnswerLoad(id_post) {
    document.getElementById(id_post + '-verify-button').onclick = () => {verify(id_post);}
    checkVerified(id_post);
}

export function onQuestionLoadSolved(id_post) {
    checkSolved(id_post);
}