<!-- Page Content START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo $title; ?></h5>
        <div class="white-in">
            <div class="tgl-content" style="display: block;">

			<?php
				echo $form->create(null, array('action' => 'index'));
				echo $form->input('name');
				echo $form->input('email');
				echo $form->input('subject');
				echo $form->input('message');
				echo $form->submit();
				echo $form->end(); 
			?>

            </div>
        </div>
    </div>
</div>
<!-- Page Content END -->