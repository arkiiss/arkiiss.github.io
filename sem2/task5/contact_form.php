<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <title>Контактная Форма</title>
</head>
<body>
<div class="container">
    <form action="" method="POST" class="form">
        <div class="form-container">
            <div class="form-header">
                <h2><b>Форма</b></h2>
            </div>

            <?php 
            function showError($name) {
                if (!empty($_COOKIE['error_' . $name])) {
                    echo '<div class="error-message">' . htmlspecialchars($_COOKIE['error_' . $name]) . '</div>';
                }
            }

            function inputClass($name) {
                return !empty($_COOKIE['error_' . $name]) ? 'error' : '';
            }

            function inputValue($name) {
                if (!empty($_COOKIE['value_' . $name])) {
                    $value = json_decode($_COOKIE['value_' . $name], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return '';
                    }
                    return htmlspecialchars($_COOKIE['value_' . $name]);
                }
                return '';
            }

            function isSelected($name, $value) {
                if (!empty($_COOKIE['value_' . $name])) {
                    $savedValues = json_decode($_COOKIE['value_' . $name], true);
                    if (is_array($savedValues)) {
                        return in_array($value, $savedValues) ? 'selected' : '';
                    }
                }
                return '';
            }

            function isChecked($name, $value) {
                return (!empty($_COOKIE['value_' . $name]) && $_COOKIE['value_' . $name] === $value) ? 'checked' : '';
            }

            function isAgreed() {
                return (!empty($_COOKIE['value_agreement'])) ? 'checked' : '';
            }
            ?>

            <div class="form-group">
                <?php showError('fullName'); ?>
                <label>ФИО</label>
                <input name="fullName" class="input <?= inputClass('fullName') ?>" 
                       value="<?= inputValue('fullName') ?>" />
            </div>

            <div class="form-group">
                <?php showError('phoneNumber'); ?>
                <label>Номер телефона</label>
                <input type="tel" name="phoneNumber" class="input <?= inputClass('phoneNumber') ?>" 
                       value="<?= inputValue('phoneNumber') ?>" />
            </div>

            <div class="form-group">
                <?php showError('userEmail'); ?>
                <label>Email</label>
                <input name="userEmail" type="email" class="input <?= inputClass('userEmail') ?>" 
                       value="<?= inputValue('userEmail') ?>" />
            </div>

            <div class="form-group">
                <?php showError('eventDate'); ?>
                <label>Дата</label>
                <input name="eventDate" class="input <?= inputClass('eventDate') ?>" type="date" 
                       value="<?= inputValue('eventDate') ?>" />
            </div>

            <div class="form-group">
                <?php showError('gender'); ?>
                <label>Пол</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="M" <?= isChecked('gender', 'M') ?> /> Муж</label>
                    <label><input type="radio" name="gender" value="W" <?= isChecked('gender', 'W') ?> /> Жен</label>
                </div>
            </div>

            <div class="form-group">
                <?php showError('selectedLanguages'); ?>
                <label>Любимый язык программирования</label>
                <select class="input <?= inputClass('selectedLanguages') ?>" name="selectedLanguages[]" multiple="multiple">
                    <?php
                    $langs = ['Pascal','C','C++','JavaScript','PHP','Python','Java','Haskel','Clojure','Scala','Go'];
                    foreach ($langs as $lang) {
                        echo "<option value=\"$lang\" " . isSelected('selectedLanguages', $lang) . ">$lang</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <?php showError('userBio'); ?>
                <label>Биография</label>
                <textarea class="input <?= inputClass('userBio') ?>" name="userBio"><?= inputValue('userBio') ?></textarea>
            </div>

            <div class="form-group">
                <?php showError('agreement'); ?>
                <label class="agreement">
                    <input type="checkbox" name="agreement" <?= isAgreed() ?> /> С условиями ознакомлен(а)
                </label>
            </div>

            <button type="submit" class="button">Отправить</button>
        </div>
    </form>
</div>
</body>
</html>