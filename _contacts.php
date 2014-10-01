<?php

function p_title() {
    return "Контакты. Спортивный-экипировочный центр Sport Class";
}

function p_description() {
    return "Контакты. Спортивный-экипировочный центр Sport Class";
}

function p_breadcrumbs() {
    return "Контакты";
}

function p_content() {
    ?>

    <style type="text/css" >
        table.vcard{
            margin-bottom: 24px;
        }
        table.vcard td, table.vcard th{
            padding: 4px 8px 4px 0;
        }

        table#rekvisit td, table#rekvisit th{
            padding: 4px 16px 4px 0;
        }
    </style>

    <table class="vcard">
        <tr>
            <td colspan="2" class="fn org heading" style="color: #0a7ca9; ">Спортивный-экипировочный центр "Sport Class"</td>
        </tr>
        <tr>
            <td><img width="24" src="img/home.png"/></td>
            <td class="adr">
                <span class="locality">г.Саратов</span>,
                <span class="street-address">ул. Гвардейская 13 (трамвайная остановка 4-дачная)</span>,
            </td>
        </tr>
        <tr><td><img width="24" src="img/time.png"/></td>  <td class="workhours">По будням с 10.00 до 19.00, суббота и воскресенье с 10.00 до 16.00</td></tr>
        <tr><td><img width="24" src="img/phone.png"/></td> <td><span class="tel">+7 (8452) 77-57-40</span></td></tr>
        <tr><td><img width="24" src="img/email.png"/></td> <td><a class="email" href="mailto:info@sportclass.com.ru">info@sportclass.com.ru</a></td></tr>
        <tr><td colspan="2" class="map">
		<script type="text/javascript" charset="utf-8" src="//api-maps.yandex.ru/services/constructor/1.0/js/?sid=zjrb1OArvdj_SY73QSmPIWWfTIeiF2nl&width=600&height=450"></script>
		</td></tr>
    </table>

    <div class="heading" style="color: #0a7ca9; ">ИП Голяков Станислав Александрович</div>
    <table id="rekvisit">
        <tr><td>ИНН </td><td>645392417241</td></tr>
        <tr><td>Юридический адрес </td><td>410062 г.Саратов ул.Моторная д.10 кв.43</td></tr>
        <tr><td>Расчетный счет </td><td>40802810256110010529</td></tr>
        <tr><td>Корр. счет </td><td>30101810500000000649</td></tr>
        <tr><td>БИК </td><td>046311649</td></tr>
        <tr><td>Банк </td><td>Саратовское ОСБ №8622 г.Саратов</td></tr>
    </table>


<?php } ?>
