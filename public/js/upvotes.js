import { sendAjaxRequest } from './ajax-request.js';

// Upvotes Ajax
function vote(voteVal, id_post) {
    sendAjaxRequest('POST','/post/vote', {id_post: id_post, vote: voteVal}, voteHandler);
}
  
function voteHandler() {
    let response = JSON.parse(this.response);
    let interactions = document.getElementById(response.id_post + '-noVotes-span');
    interactions.innerHTML = response.no_votes;
    checkVote(response.id_post);
}

function checkVote(id_post) {
    sendAjaxRequest('GET','/post/' + id_post + '/rating', null, checkVoteHandler);
}

function checkVoteHandler() {
    let response = JSON.parse(this.response);
    document.getElementById(response.id_post + '-upvote-button').style = "background: #287BE5;";
    document.getElementById(response.id_post + '-downvote-button').style = "background: #287BE5;";
    if (response.rating != null) {

        if (response.rating.rating) {
            document.getElementById(response.id_post + '-upvote-button').style = "background: #134A91 ;";
        } else {
            document.getElementById(response.id_post + '-downvote-button').style = "background: #134A91 ;";
        }
    }
}

export function onPostLoad(id_post) {
    console.log('Ia');
    document.getElementById(id_post + '-upvote-button').onclick = () => {vote(true, id_post);};
    document.getElementById(id_post + '-downvote-button').onclick = () => {vote(false, id_post);};
    checkVote(id_post);
}

