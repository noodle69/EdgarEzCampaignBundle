document.addEventListener('DOMContentLoaded', function () {
    const link = document.querySelectorAll('.edgarcampaign-btn');
    const linkTrigger = (event) => {
        event.preventDefault();

        const link = event.currentTarget;
        const linkAction = link.dataset.action;
        const linkTarget = link.dataset.target;

        const modalForm = document.querySelector(linkTarget + ' form');
        modalForm.setAttribute('action', linkAction);
    };

    link.forEach(link => link.addEventListener('click', linkTrigger, false));
}, false);
