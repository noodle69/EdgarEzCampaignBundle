(function () {
    const btnCreate = document.querySelector('#campaign_create_content_select_content');
    const btnEdit = document.querySelector('#update-campaign-0_content_select_content');
    const btnCreateContent = document.querySelector('#campaign_create_content_content_select_content');

    const udwContainer = document.getElementById('react-udw');
    const token = document.querySelector('meta[name="CSRF-Token"]').content;
    const siteaccess = document.querySelector('meta[name="SiteAccess"]').content;
    const closeUDW = () => udwContainer.innerHTML = '';

    const selectedLocationCreateId = document.querySelector('#campaign_create_content_location');
    const selectedLocationCreateName = document.querySelector('#campaign_create_content_select_content_label');
    const selectedLocationEditId = document.querySelector('#campaign_create_content_content_select_content');
    const selectedLocationEditName = document.querySelector('#campaign_edit_content_select_content_label');
    const selectedLocationCreateContentId = document.querySelector('#campaign_create_content_content_location');
    const selectedLocationCreateContentName = document.querySelector('#campaign_create_content_select_content_label');

    const onConfirm = (form, content) => {
        arrayLocationIds = [];
        arrayContentNames = [];

        content.forEach(function (item) {
            arrayLocationIds.push(item.id);
            arrayContentNames.push('<li>' + item.ContentInfo.Content.Name + '</li>');
        });

        if (selectedLocationCreateId) {
            selectedLocationCreateId.value = arrayLocationIds.join(',');
        }
        if (selectedLocationCreateName) {
            selectedLocationCreateName.innerHTML = arrayContentNames.join('');
        }

        if (selectedLocationEditId) {
            selectedLocationEditId.value = arrayLocationIds.join(',');
        }
        if (selectedLocationEditName) {
            selectedLocationEditName.innerHTML = arrayContentNames.join('');
        }

        if (selectedLocationCreateContentId) {
            selectedLocationCreateContentId.value = arrayLocationIds.join(',');
        }
        if (selectedLocationCreateContentName) {
            selectedLocationCreateContentName.innerHTML = arrayContentNames.join('');
        }

        closeUDW();
    };
    const onCancel = () => closeUDW();
    const openUDW = (event) => {
        event.preventDefault();

        if (selectedLocationCreateId) {
            selectedLocationCreateId.value = '';
        }
        if (selectedLocationCreateName) {
            selectedLocationCreateName.innerHTML = '';
        }

        if (selectedLocationEditId) {
            selectedLocationEditId.value = '';
        }
        if (selectedLocationEditName) {
            selectedLocationEditName.innerHTML = '';
        }

        if (selectedLocationCreateContentId) {
            selectedLocationCreateContentId.value = '';
        }
        if (selectedLocationCreateContentName) {
            selectedLocationCreateContentName.innerHTML = '';
        }

        const form = document.querySelector('form[name="edgarfiltercontentstype"]');

        ReactDOM.render(React.createElement(eZ.modules.UniversalDiscovery, {
            onConfirm: onConfirm.bind(this, form),
            onCancel,
            startingLocationId: window.eZ.adminUiConfig.universalDiscoveryWidget.startingLocationId,
            restInfo: {token, siteaccess}
        }), udwContainer);
    };

    if (btnCreate) {
        btnCreate.addEventListener('click', openUDW, false);
    }

    if (btnEdit) {
        btnEdit.addEventListener('click', openUDW, false);
    }

    if (btnCreateContent) {
        btnCreateContent.addEventListener('click', openUDW, false);
    }
})();
