<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="bootstrap.min.css" />
    <title>Контактная Форма</title>
</head>
<body>
<form action="" method="POST" class="form">
    <div class="form-container">
        <div class="form-header">
            <h2><b>Форма</b></h2>
        </div>

        <?php function showError($name) {
            if (!empty($_COOKIE[$name . '_error'])) {
                echo '<div class="error-message">' . $_COOKIE[$name . '_error'] . '</div>';
            }
        }

        function inputClass($name) {
            return !empty($_COOKIE[$name . '_error']) ? 'input error' : 'input';
        }

        function inputValue($name) {
            return !empty($_COOKIE[$name . '_value']) ? htmlspecialchars($_COOKIE[$name . '_value']) : '';
        }

        function isSelected($name, $value) {
            return (!empty($_COOKIE[$name . '_value']) && in_array($value, explode(',', $_COOKIE[$name . '_value']))) ? 'selected' : '';
        }

        function isChecked($name, $value) {
            return (!empty($_COOKIE[$name . '_value']) && $_COOKIE[$name . '_value'] === $value) ? 'checked' : '';
        }

        function isAgreed() {
            return (!empty($_COOKIE['agreement_value'])) ? 'checked' : '';
        }
        ?>

        <div class="form-group">
            <?php showError('fullName'); ?>
            <label> <input name="fullName" class="<?= inputClass('fullName') ?>" placeholder="ФИО" value="<?= inputValue('fullName') ?>" /> </label>
        </div>

        <div class="form-group">
            <?php showError('phoneNumber'); ?>
            <label> <input type="tel" name="phoneNumber" class="<?= inputClass('phoneNumber') ?>" placeholder="Номер телефона" value="<?= inputValue('phoneNumber') ?>" /> </label>
        </div>

        <div class="form-group">
            <?php showError('userEmail'); ?>
            <label> <input name="userEmail" type="email" class="<?= inputClass('userEmail') ?>" placeholder="Почта" value="<?= inputValue('userEmail') ?>" /> </label>
        </div>

        <div class="form-group">
            <?php showError('eventDate'); ?>
            <label> <input name="eventDate" class="<?= inputClass('eventDate') ?>" type="date" value="<?= inputValue('eventDate') ?>" /> </label>
        </div>

        <div class="form-group">
            <?php showError('gender'); ?>
            <div>Пол</div>
            <div class="gender-options">
                <label> <input class="ml-2" type="radio" name="gender" value="M" <?= isChecked('gender', 'M') ?> /> Муж </label>
                <label> <input class="ml-4" type="radio" name="gender" value="W" <?= isChecked('gender', 'W') ?> /> Жен </label>
            </div>
        </div>

        <div class="form-group">
            <?php showError('selectedLanguages'); ?>
            <label class="<?= inputClass('selectedLanguages') ?>">
                <div>Любимый язык программирования</div>
                <select class="my-2 <?= inputClass('selectedLanguages') ?>" name="selectedLanguages[]" multiple="multiple">
                    <?php
                    $langs = ['Pascal','C','C++','JavaScript','PHP','Python','Java','Haskel','Clojure','Scala','Go'];
                    foreach ($langs as $lang) {
                        $selected = isSelected('selectedLanguages', $lang);
                        echo "<option value=\"$lang\" $selected>$lang</option>";
                    }
                    ?>
                </select>
            </label>
        </div>

        <div class="form-group">
            <?php showError('userBio'); ?>
            <div>Биография</div>
            <label>
                <textarea class="<?= inputClass('userBio') ?>" name="userBio" placeholder="Биография"><?= inputValue('userBio') ?></textarea>
            </label>
        </div>

        <div class="form-group">
            <?php showError('agreement'); ?>
            <label class="agreement">
                <label> <input id="agreement" type="checkbox" name="agreement" <?= isAgreed() ?> /> с условиями ознакомлен(а) </label>
            </label>
        </div>

        <button type="submit" class="button my-3">Отправить</button>
    </div>
</form>
</body>
</html>
