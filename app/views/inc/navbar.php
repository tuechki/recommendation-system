<div class="nav">
    <a id="home-link" href="<?php echo URLROOT; ?>/"><img id="fmi-logo" src="<?php echo URLROOT; ?>/public/img/fmi-logo.svg" alt="Home"></a>
    <?php if(isset($_SESSION['user_id'])) : ?>
        
            <a class="nav-link" href="<?php echo URLROOT; ?>/curriculums/index">Учебни планове</a>
            <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/index">Дисциплини</a>
        <?php if($_SESSION['user_role'] != 'admin') : ?>
                <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/enrolled">Записани дисциплини</a>
            <?php endif; ?>
            <?php if($_SESSION['user_role'] == 'admin') : ?>
                <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/import" class="btn">Добави дисциплини</a>
                <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/stats" class="btn">Статистики</a>
        <?php endif; ?>
            <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/export" class="btn">Експортиране</a>

        <div class="right">
            <?php if($_SESSION['user_role'] == 'admin') : ?>
                <p><?php echo $_SESSION['user_name']; ?></p>
            <?php else: ?>
                <a class="nav-link" id="profile" href="<?php echo URLROOT; ?>/users/profile"><?php echo $_SESSION['user_name']; ?></a>
            <?php endif; ?>
            <a class="nav-link" id="logout" href="<?php echo URLROOT; ?>/users/logout">Изход</a>
        </div> 
       
        <?php else : ?>
            <a class="nav-link" href="<?php echo URLROOT; ?>/curriculums/index">Учебни планове</a>
            <a class="nav-link" href="<?php echo URLROOT; ?>/disciplines/index">Дисциплини</a>
        <div class="right">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Регистрация</a>
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Вход</a>
        </div>        
        <?php endif; ?>
      
</div>