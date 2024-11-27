<style>
    label {
        line-height:38px;
    }

    textarea#body {
        height: 350px;
    }

    .btnCancel {
        background-color: red;
        border-color: red;
    }
    .btnCancel:hover {
        background-color: #f84545;
        border-color: #f84545;
    }

    #templateTags {
        display:flex;
        flex-wrap:wrap;
        justify-content: flex-start;
        align-items: stretch;
    }
    .btnSmartTag {
        display: block;
        margin: 1%;
        padding: 0.5em;
        width:31%;

        line-height: 1;
        white-space: unset;
    }

</style>
<div class="box-typical box-typical-padding">
	<?= $this->load->view('templates/breadcrumbs'); ?>
	<section>
		<?php if(!$canEdit){ ?>
			<div class="alert alert-warning">
				<strong>Please Note;</strong> You don't have permission to edit Email Template!.
			</div>
		<?php } ?>
		<div class="body-typical-body">
			<div class="g_form">


					<div class="row">
						<div class="col-md-7">
								<form method="POST" id="template_form" action="/email/save_email_template/<?= $data['email_templates_id'] ?? '' ?>">
                                    <input type="hidden" name="email_templates_id" value="<?= $data['email_templates_id'] ?>">
                                    <input type="hidden" id="smartTagTarget" value="" />







                                    <div class="row form-group">
                                        <div class="col-sm-2">
                                            <label class="form-control-label">
                                                Template Name <span class="text-red">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <input required="required" value="<?= $data['template_name'] ?>" type="text" class="form-control addinput template_name" name="template_name" id="template_name" <?= !$canEdit ? 'disabled' : ''; ?> />
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-2">
                                            <label class="form-control-label">
                                                Subject <span class="text-red">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <input required="required" value="<?= $data['subject'] ?>" type="text" class="form-control addinput subject" name="subject" id="subject" <?= !$canEdit ? 'disabled' : ''; ?> />
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-sm-2">
                                            <label class="form-control-label">
                                                Body <span class="text-red">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <textarea required="required" name="body" id="body" class="form-control addtextarea body" <?= !$canEdit ? 'disabled' : ''; ?>><?= $data['body'] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-sm-2">
                                            <label class="form-control-label">
                                                Type <span class="text-red">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
											<?php $this->load->view('helpers/select', $selectTemplateTypes); ?>
                                        </div>
                                    </div>



                                    <div class="row form-group">
                                        <div class="col-sm-2">
                                            <label class="form-control-label">
                                                Call Centre <span class="text-red">*</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <select required="required" name="show_to_call_centre" id="show_to_call_centre" class="form-control addinput" <?= !$canEdit ? 'disabled' : ''; ?>>
                                                <option value="1" <?= ( $data['show_to_call_centre'] !== 0 ) ? 'selected="selected"' : ''; ?>>Yes</option>
                                                <option value="0" <?= ( $data['show_to_call_centre'] === 0 ) ? 'selected="selected"' : ''; ?>>No</option>
                                            </select>
                                        </div>
                                    </div>

									<?php if($data['email_templates_id']): ?>
                                        <div class="row form-group">
                                            <div class="col-sm-2">
                                                <label class="form-control-label">
                                                    Active <span class="text-red">*</span>
                                                </label>
                                            </div>
                                            <div class="col-sm-10">
                                                <select name="active" id="active" class="addinput form-control" <?= !$canEdit ? 'disabled' : ''; ?>>
                                                    <option value="1" <?= ( $data['et_active'] !== 0 ) ? 'selected="selected"' : ''; ?>>Yes</option>
                                                    <option value="0" <?= ( $data['et_active'] === 0 ) ? 'selected="selected"' : ''; ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
									<?php else: ?>
                                        <input type="hidden" name="active" value="1">
									<?php endif; ?>


									<?php if($canEdit): ?>
										<div class="row">
                                            <div class="col-md-6">
                                                <button class="submitbtnImg  btn btnCancel" id="btn_clear" type="button">
                                                    Clear
                                                </button>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button class="submitbtnImg btn btnSubmit" id="btn_submit" type="submit">
                                                    Save
                                                </button>
                                            </div>
										</div>
									<?php endif; ?>
								</form>

						</div>
						<div class="col-md-5">
							<div id="templateTags">
								<?php if(!empty($templateTags)): ?>
									<?php foreach ($templateTags as $temp_tag): ?>
                                        <button id="temp_tag_btn<?= $temp_tag['email_templates_tag_id'] ?>" class="btn btnSmartTag" title="<?= $temp_tag['tag'] ?>">
                                            <?= $temp_tag['tag_name'] ?>
                                        </button>
                                    <?php endforeach; ?>
                                <?php endif; ?>
							</div>
						</div>
					</div>



			</div>
		</div>
	</section>

</div>



<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4>Add/Edit Email Template</h4>
	<p>Emails that can be sent out via the CRM are managed here.</p>
    <p>Call center decides whether the staff in the call centre can see and use the template</p>
    <p>Active dictates whether it will be shown by default on page load, an inactive template cannot be used anywhere</p>

</div>
<script>
    function typeInTextarea(el, newText) {
        if(!el.length){
            alert('First click within the "Subject" or "Body" fields to specify where to insert the smart tag.')
            return false;
        }
        //console.log(el,newText);

        // starting text highlight position
        var start = el.prop("selectionStart");
        //console.log("selection start: "+start+" \n");
        // end text highlight position
        var end = el.prop("selectionEnd");
        //console.log("selection start: "+end+" \n");
        // text area original text
        var text = el.val();
        // before text of the inserted location
        var before = text.substring(0, start);
        // after text of the inserted location
        var after = text.substring(end, text.length);
        // combine texts
        el.val(before + newText + after);
        // put text cursor at the end of the insertd tag
        el[0].selectionStart = el[0].selectionEnd = start + newText.length;
        // displat text cursor
        el.focus();
    }

    $(document).ready(function () {
        var smartTagTargetSelector = $("#smartTagTarget").val();
        
        $(".btnSmartTag").on("click", function () {
            var tag = $(this).attr("title");
            smartTagTargetSelector = $("#smartTagTarget").val();
            typeInTextarea($(smartTagTargetSelector), tag);
            return false;
        });

        // Set the target for when a btn is clicked
        $("#subject").click(function () {
            $("#smartTagTarget").val("input#subject");
        });

        $("#body").click(function () {
            $("#smartTagTarget").val("textarea#body");
        });

        // clear
        $("#btn_clear").click(function () {
            $("#template_name, #subject, #body").val("");
        });


        $("#template_form").submit(function () {

            var template_name = $("#template_name").val();
            var subject = $("#subject").val();
            var body = $("#body").val();
            var temp_type = $("#temp_type").val();
            var show_to_call_centre = $("#show_to_call_centre").val();
            var error = "";

            if (template_name == "") {
                error += "Template Name is Required \n";
            }

            if (subject == "") {
                error += "Subject is Required \n";
            }

            if (temp_type == "") {
                error += "Template Type is Required \n";
            }

            if (body == "") {
                error += "Email Body is Required \n";
            }

            if (show_to_call_centre == "") {
                error += "Call Centre is Required \n";
            }

            if (error != "") {
                alert(error);
                return false;
            } else {
                return true;
            }

        });


    });
</script>