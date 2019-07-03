<template>
    <div class="comment-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-12">
                        <div
                            class="col-xs-6"
                            align="left"
                        >
                            <p>Commentaire du client </p>
                        </div>
                        <div
                            class="col-xs-6"
                            align="right"
                        >
                            <img
                                data-dismiss="modal"
                                src="/storage/bobby_images/marketplace-client/icon-cross.svg"
                            >
                        </div>
                    </div>
                </div>

                <p style="margin-left: 30px;">
                    {{ command.commentaire_client }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CommentModal',
    props: {
        command: { type: Object, required: true }
    }
};
</script>
