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

        <div class="form-group">
            <label> <input name="fullName" class="input" placeholder="ФИО" /> </label>
        </div>

        <div class="form-group">
            <label> <input type="tel" name="phoneNumber" class="input" placeholder="Номер телефона" /> </label>
        </div>

        <div class="form-group">
            <label> <input name="userEmail" type="email" class="input" placeholder="Почта" /> </label>
        </div>

        <div class="form-group">
            <label>
                <input name="eventDate" class="input" type="date" />
            </label>
        </div>

        <div class="form-group">
            <div>Пол</div>
            <div class="gender-options">
                <label> <input class="ml-2" type="radio" name="gender" value="M" /> Муж </label>
                <label> <input class="ml-4" type="radio" name="gender" value="W" /> Жен </label>
            </div>
        </div>

        <div class="form-group">
            <label class="input">
                <div>Любимый язык программирования</div>
                <select class="my-2" name="selectedLanguages[]" multiple="multiple">
                    <option value="Pascal">Pascal</option>
                    <option value="C">C</option>
                    <option value="C++">C++</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="PHP">PHP</option>
                    <option value="Python">Python</option>
                    <option value="Java">Java</option>
                    <option value="Haskel">Haskel</option>
                    <option value="Clojure">Clojure</option>
                    <option value="Scala">Scala</option>
                    <option value="Go">Go</option>
                </select>
            </label>
        </div>

        <div class="form-group">
            <div>Биография</div>
            <label>
                <textarea class="input" name="userBio" placeholder="Биография"></textarea>
            </label>
        </div>

        <div class="form-group">
            <label class="agreement">
                <label> <input id="agreement" type="checkbox" name="agreement" /> с условиями ознакомлен(а) </label>
            </label>
        </div>

        <button type="submit" class="button my-3">Отправить</button>
    </div>
</form>
</body>
</html>