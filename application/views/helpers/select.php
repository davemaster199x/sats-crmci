<select id="<?= $id ?>" name="<?= $name ?>" class="<?= $class ?>" <?= $disabled ? ' disabled="disabled"':''; ?> <?= $required ? ' required="required"':''; ?>>
<option value="">Please Select...</option>
    <?php foreach($options as $option): ?>
	<?php $this->load->view('helpers/option', $option); ?>
<?php endforeach; ?>
</select>
