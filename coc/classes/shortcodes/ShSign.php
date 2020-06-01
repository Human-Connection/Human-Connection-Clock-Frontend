<?php

namespace coc\shortcodes;

use coc\ClockOfChange;
use coc\core\CoCAPI;
use coc\core\Translation;

// coc\shortcodes\shsign
class ShSign
{
    /**
     * @var CoCAPI
     */
    private $api;

    /**
     * ShSign constructor.
     *
     * @param CoCAPI      $api
     * @param Translation $translation
     */
    private $translation;

    public function __construct($api, $translation)
    {
        $this->api         = $api;
        $this->translation = $translation;

        add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
    }

    /**
     *
     */
    public function renderShortcode($atts, $content)
    {
        $html = '';

        // overlay and form - default invis
        $html .= '<div class="coc-form-overlay">';
        $html .= '<div class="coc-form">';
        $html .= '<span class="coc-form-close">&times;</span>';
        $html .= '<div id="form-error"></div>';
        $html .= '<span class="avatar-label label">' . $this->translation->t('avatar', 'Avatar') . '</span>';
        $html .= '<p class="hint">' . $this->translation->t('minSizeAvatar', 'min. 200*200px') . '</p>';
        $html .= '<div class="form-group" id="coc-add-avatar">';
        $html .= '<div id="add-avatar-inner">';
        $html .= '<div class="avatar-preview" id="avatar-preview">';

        // preview image
        $html .= '<div class="preview-wrapper">';
        $html .= '<img src="' . ClockOfChange::$pluginAssetsUri . '/images/coc-placeholder.jpg" id="coc-image" name="image" />';
        $html .= '</div>';

        $html .= '<input type="file" id="ownImageUpload" style="display:none" autocomplete="off">';
        $html .= '<div class="upload-own-wrapper" id="customUploadWrap">';
        $html .= '<i title="' . $this->translation->t('uploadOwnImage', 'Eigenes Bild hochladen') . '" id="upload-icon" class="fas fa-upload large-icon"></i>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="avatar-wrapper">';
        $html .= '</div>';
        $html .= '<div class="input-group">';
        $html .= '<div class="form-group">';
        $html .= '<label class="label" for="coc-firstname">' . $this->translation->t('firstName', 'Vorname') . ' *</label>';
        $html .= '<input type="text" id="coc-firstname" name="coc-firstname" autocomplete="off" />';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="label" for="coc-lastname">' . $this->translation->t('lastName', 'Nachname') . '</label>';
        $html .= '<input type="text" id="coc-lastname" name="coc-lastname" autocomplete="off" />';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="label" for="coc-email">' . $this->translation->t('emailAddress', 'E-Mail Adresse') . ' *</label>';
        $html .= '<input type="text" id="coc-email" name="coc-email" autocomplete="off" />';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="label" for="coc-country">' . $this->translation->t('country', 'Land') . '</label><br />';
        $html .= '</div>';
        $html .= <<<EOT
<select id="coc-country" name="coc-country" autocomplete="off">
	<option value="AF">Afghanistan</option>
	<option value="AX">Åland Islands</option>
	<option value="AL">Albania</option>
	<option value="DZ">Algeria</option>
	<option value="AS">American Samoa</option>
	<option value="AD">Andorra</option>
	<option value="AO">Angola</option>
	<option value="AI">Anguilla</option>
	<option value="AQ">Antarctica</option>
	<option value="AG">Antigua and Barbuda</option>
	<option value="AR">Argentina</option>
	<option value="AM">Armenia</option>
	<option value="AW">Aruba</option>
	<option value="AU">Australia</option>
	<option value="AT">Austria</option>
	<option value="AZ">Azerbaijan</option>
	<option value="BS">Bahamas</option>
	<option value="BH">Bahrain</option>
	<option value="BD">Bangladesh</option>
	<option value="BB">Barbados</option>
	<option value="BY">Belarus</option>
	<option value="BE">Belgium</option>
	<option value="BZ">Belize</option>
	<option value="BJ">Benin</option>
	<option value="BM">Bermuda</option>
	<option value="BT">Bhutan</option>
	<option value="BO">Bolivia, Plurinational State of</option>
	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
	<option value="BA">Bosnia and Herzegovina</option>
	<option value="BW">Botswana</option>
	<option value="BV">Bouvet Island</option>
	<option value="BR">Brazil</option>
	<option value="IO">British Indian Ocean Territory</option>
	<option value="BN">Brunei Darussalam</option>
	<option value="BG">Bulgaria</option>
	<option value="BF">Burkina Faso</option>
	<option value="BI">Burundi</option>
	<option value="KH">Cambodia</option>
	<option value="CM">Cameroon</option>
	<option value="CA">Canada</option>
	<option value="CV">Cape Verde</option>
	<option value="KY">Cayman Islands</option>
	<option value="CF">Central African Republic</option>
	<option value="TD">Chad</option>
	<option value="CL">Chile</option>
	<option value="CN">China</option>
	<option value="CX">Christmas Island</option>
	<option value="CC">Cocos (Keeling) Islands</option>
	<option value="CO">Colombia</option>
	<option value="KM">Comoros</option>
	<option value="CG">Congo</option>
	<option value="CD">Congo, the Democratic Republic of the</option>
	<option value="CK">Cook Islands</option>
	<option value="CR">Costa Rica</option>
	<option value="CI">Côte d'Ivoire</option>
	<option value="HR">Croatia</option>
	<option value="CU">Cuba</option>
	<option value="CW">Curaçao</option>
	<option value="CY">Cyprus</option>
	<option value="CZ">Czech Republic</option>
	<option value="DK">Denmark</option>
	<option value="DJ">Djibouti</option>
	<option value="DM">Dominica</option>
	<option value="DO">Dominican Republic</option>
	<option value="EC">Ecuador</option>
	<option value="EG">Egypt</option>
	<option value="SV">El Salvador</option>
	<option value="GQ">Equatorial Guinea</option>
	<option value="ER">Eritrea</option>
	<option value="EE">Estonia</option>
	<option value="ET">Ethiopia</option>
	<option value="FK">Falkland Islands (Malvinas)</option>
	<option value="FO">Faroe Islands</option>
	<option value="FJ">Fiji</option>
	<option value="FI">Finland</option>
	<option value="FR">France</option>
	<option value="GF">French Guiana</option>
	<option value="PF">French Polynesia</option>
	<option value="TF">French Southern Territories</option>
	<option value="GA">Gabon</option>
	<option value="GM">Gambia</option>
	<option value="GE">Georgia</option>
	<option value="DE" selected>Germany</option>
	<option value="GH">Ghana</option>
	<option value="GI">Gibraltar</option>
	<option value="GR">Greece</option>
	<option value="GL">Greenland</option>
	<option value="GD">Grenada</option>
	<option value="GP">Guadeloupe</option>
	<option value="GU">Guam</option>
	<option value="GT">Guatemala</option>
	<option value="GG">Guernsey</option>
	<option value="GN">Guinea</option>
	<option value="GW">Guinea-Bissau</option>
	<option value="GY">Guyana</option>
	<option value="HT">Haiti</option>
	<option value="HM">Heard Island and McDonald Islands</option>
	<option value="VA">Holy See (Vatican City State)</option>
	<option value="HN">Honduras</option>
	<option value="HK">Hong Kong</option>
	<option value="HU">Hungary</option>
	<option value="IS">Iceland</option>
	<option value="IN">India</option>
	<option value="ID">Indonesia</option>
	<option value="IR">Iran, Islamic Republic of</option>
	<option value="IQ">Iraq</option>
	<option value="IE">Ireland</option>
	<option value="IM">Isle of Man</option>
	<option value="IL">Israel</option>
	<option value="IT">Italy</option>
	<option value="JM">Jamaica</option>
	<option value="JP">Japan</option>
	<option value="JE">Jersey</option>
	<option value="JO">Jordan</option>
	<option value="KZ">Kazakhstan</option>
	<option value="KE">Kenya</option>
	<option value="KI">Kiribati</option>
	<option value="KP">Korea, Democratic People's Republic of</option>
	<option value="KR">Korea, Republic of</option>
	<option value="KW">Kuwait</option>
	<option value="KG">Kyrgyzstan</option>
	<option value="LA">Lao People's Democratic Republic</option>
	<option value="LV">Latvia</option>
	<option value="LB">Lebanon</option>
	<option value="LS">Lesotho</option>
	<option value="LR">Liberia</option>
	<option value="LY">Libya</option>
	<option value="LI">Liechtenstein</option>
	<option value="LT">Lithuania</option>
	<option value="LU">Luxembourg</option>
	<option value="MO">Macao</option>
	<option value="MK">Macedonia, the former Yugoslav Republic of</option>
	<option value="MG">Madagascar</option>
	<option value="MW">Malawi</option>
	<option value="MY">Malaysia</option>
	<option value="MV">Maldives</option>
	<option value="ML">Mali</option>
	<option value="MT">Malta</option>
	<option value="MH">Marshall Islands</option>
	<option value="MQ">Martinique</option>
	<option value="MR">Mauritania</option>
	<option value="MU">Mauritius</option>
	<option value="YT">Mayotte</option>
	<option value="MX">Mexico</option>
	<option value="FM">Micronesia, Federated States of</option>
	<option value="MD">Moldova, Republic of</option>
	<option value="MC">Monaco</option>
	<option value="MN">Mongolia</option>
	<option value="ME">Montenegro</option>
	<option value="MS">Montserrat</option>
	<option value="MA">Morocco</option>
	<option value="MZ">Mozambique</option>
	<option value="MM">Myanmar</option>
	<option value="NA">Namibia</option>
	<option value="NR">Nauru</option>
	<option value="NP">Nepal</option>
	<option value="NL">Netherlands</option>
	<option value="NC">New Caledonia</option>
	<option value="NZ">New Zealand</option>
	<option value="NI">Nicaragua</option>
	<option value="NE">Niger</option>
	<option value="NG">Nigeria</option>
	<option value="NU">Niue</option>
	<option value="NF">Norfolk Island</option>
	<option value="MP">Northern Mariana Islands</option>
	<option value="NO">Norway</option>
	<option value="OM">Oman</option>
	<option value="PK">Pakistan</option>
	<option value="PW">Palau</option>
	<option value="PS">Palestinian Territory, Occupied</option>
	<option value="PA">Panama</option>
	<option value="PG">Papua New Guinea</option>
	<option value="PY">Paraguay</option>
	<option value="PE">Peru</option>
	<option value="PH">Philippines</option>
	<option value="PN">Pitcairn</option>
	<option value="PL">Poland</option>
	<option value="PT">Portugal</option>
	<option value="PR">Puerto Rico</option>
	<option value="QA">Qatar</option>
	<option value="RE">Réunion</option>
	<option value="RO">Romania</option>
	<option value="RU">Russian Federation</option>
	<option value="RW">Rwanda</option>
	<option value="BL">Saint Barthélemy</option>
	<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
	<option value="KN">Saint Kitts and Nevis</option>
	<option value="LC">Saint Lucia</option>
	<option value="MF">Saint Martin (French part)</option>
	<option value="PM">Saint Pierre and Miquelon</option>
	<option value="VC">Saint Vincent and the Grenadines</option>
	<option value="WS">Samoa</option>
	<option value="SM">San Marino</option>
	<option value="ST">Sao Tome and Principe</option>
	<option value="SA">Saudi Arabia</option>
	<option value="SN">Senegal</option>
	<option value="RS">Serbia</option>
	<option value="SC">Seychelles</option>
	<option value="SL">Sierra Leone</option>
	<option value="SG">Singapore</option>
	<option value="SX">Sint Maarten (Dutch part)</option>
	<option value="SK">Slovakia</option>
	<option value="SI">Slovenia</option>
	<option value="SB">Solomon Islands</option>
	<option value="SO">Somalia</option>
	<option value="ZA">South Africa</option>
	<option value="GS">South Georgia and the South Sandwich Islands</option>
	<option value="SS">South Sudan</option>
	<option value="ES">Spain</option>
	<option value="LK">Sri Lanka</option>
	<option value="SD">Sudan</option>
	<option value="SR">Suriname</option>
	<option value="SJ">Svalbard and Jan Mayen</option>
	<option value="SZ">Swaziland</option>
	<option value="SE">Sweden</option>
	<option value="CH">Switzerland</option>
	<option value="SY">Syrian Arab Republic</option>
	<option value="TW">Taiwan, Province of China</option>
	<option value="TJ">Tajikistan</option>
	<option value="TZ">Tanzania, United Republic of</option>
	<option value="TH">Thailand</option>
	<option value="TL">Timor-Leste</option>
	<option value="TG">Togo</option>
	<option value="TK">Tokelau</option>
	<option value="TO">Tonga</option>
	<option value="TT">Trinidad and Tobago</option>
	<option value="TN">Tunisia</option>
	<option value="TR">Turkey</option>
	<option value="TM">Turkmenistan</option>
	<option value="TC">Turks and Caicos Islands</option>
	<option value="TV">Tuvalu</option>
	<option value="UG">Uganda</option>
	<option value="UA">Ukraine</option>
	<option value="AE">United Arab Emirates</option>
	<option value="GB">United Kingdom</option>
	<option value="US">United States</option>
	<option value="UM">United States Minor Outlying Islands</option>
	<option value="UY">Uruguay</option>
	<option value="UZ">Uzbekistan</option>
	<option value="VU">Vanuatu</option>
	<option value="VE">Venezuela, Bolivarian Republic of</option>
	<option value="VN">Viet Nam</option>
	<option value="VG">Virgin Islands, British</option>
	<option value="VI">Virgin Islands, U.S.</option>
	<option value="WF">Wallis and Futuna</option>
	<option value="EH">Western Sahara</option>
	<option value="YE">Yemen</option>
	<option value="ZM">Zambia</option>
	<option value="ZW">Zimbabwe</option>
</select>
EOT;
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="label" for="coc-message">' . $this->translation->t('message', 'Deine Botschaft an die Welt') . ' *</label>';
        $html .= '<textarea id="coc-message" name="coc-message" autocomplete="off"></textarea>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<input type="checkbox" id="coc-anon" name="coc-anon" autocomplete="off" />';
        $html .= '<label class="label" for="coc-anon">' . $this->translation->t('hideName', 'Meinen Namen nicht anzeigen') . '</label>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<input type="checkbox" id="coc-register-nl" name="coc-register-nl" autocomplete="off" />';
        $html .= '<label class="label" for="coc-register-nl">' . $this->translation->t('signupNewsletter', 'Für den Human Connection Newsletter eintragen') . '</label>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<input type="checkbox" id="coc-register-privacy" name="coc-register-privacy" autocomplete="off"/>';
        $html .= '<label class="label" for="coc-register-privacy">' . $this->translation->t('acceptDataPrivacyPolicy', 'Ich habe die <a class="inline-link" href="https://human-connection.org/datenschutz/#_privacycoc" title="zu den Datenschutzbestimmungen" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiere diese.') . '</label>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<input type="checkbox" id="coc-register-age" name="coc-register-age" autocomplete="off"/>';
        $html .= '<label class="label" for="coc-register-age">' . $this->translation->t('verifyOverEighteen', 'Ich bin 18 Jahre alt oder älter') . '</label>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<p class="hint">' . $this->translation->t('noticeEntryIsFree', 'Die Teilnahme ist 100% kostenlos, als gemeinnützige Organisation freuen wir uns jedoch über eine Spende. DANKE im Voraus!') . '</p>';
        $html .= '<p class="hint">' . $this->translation->t('noticeActivateAccount', 'BITTE BEACHTEN: Der Eintrag muss noch einmal von Euch bestätigt werden. Dazu bitte den Bestätigungslink in der Email klicken, welche Ihr von uns bekommt. Falls keine Email eingeht, bitte im SPAM nachschauen und am besten Human Connection als sicheren Absender kennzeichnen. Die Teilnahme an der Uhr des Wandels ist keine automatische Teilnahme im Human Connection Netzwerk.<br>Falls Du Dich im Netzwerk registrieren möchtest, folge bitte diesem Link: <a href="https://human-connection.social/" style="display: inline-block; width: auto;">https://human-connection.social/</a>. Die Teilnahme ist 100% kostenlos, Human Connection ist als gemeinnützig anerkannt.') . '</p>';
        $html .= '<a href="#" id="joinNowBtn" class="cocBtn">' . $this->translation->t('joinNow', 'Eintragen') . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return html_entity_decode($html);
    }
}
