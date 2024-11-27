<style>
    a.llv-active {
        color:#FFF !important;
        background-color: #00a8ff !important;
    }
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<div class="list-group">
				<?php if(empty($files)): ?>
					<a class="list-group-item liv-active">No Log Files Found</a>
				<?php else: ?>
					<?php foreach($files as $file): ?>
						<a href="?f=<?= base64_encode($file); ?>"
						   class="list-group-item <?= ($currentFile == $file) ? "llv-active" : "" ?>">
							<?= $file; ?>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-sm-9 col-md-10 table-container">
			<?php if(is_null($logs)): ?>
				<div>
					<br><br>
					<strong>Log file > 50MB, please download it.</strong>
					<br><br>
				</div>
			<?php else: ?>
				<table id="table-log" class="table table-striped">
					<thead>
					<tr>
						<th>Level</th>
						<th>Date</th>
						<th>Content</th>
						<th></th>
					</tr>
					</thead>
					<tbody>

					<?php foreach($logs as $key => $log): ?>
						<tr data-display="stack<?= $key; ?>">

							<td class="align-middle text-<?= $log['class']; ?>">
								<span class="<?= $log['icon']; ?>" aria-hidden="true"></span>
								&nbsp;<?= $log['level']; ?>
							</td>
							<td class="align-middle date"><?= $log['date']; ?></td>
							<td class="align-middle text">
								<?= $log['content']; ?>
								<?php if (array_key_exists("extra", $log)): ?>
									<div class="stack" id="stack<?= $key; ?>"
									     style="display: none; white-space: pre-wrap;">
										<?= $log['extra'] ?>
									</div>
								<?php endif; ?>
							</td>
                            <td class="align-top">
	                            <?php if (array_key_exists("extra", $log)): ?>
                                    <a class="expand btn btn-default btn-xs"
                                       data-display="stack<?= $key; ?>">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </a>
	                            <?php endif; ?>
                            </td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<div>
				<?php if($currentFile): ?>
					<a href="?dl=<?= base64_encode($currentFile); ?>">
						<span class="glyphicon glyphicon-download-alt"></span>
						Download file
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>