<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Contao\Widget;


use Contao\Date;
use Contao\Widget;

class PeriodWidget extends Widget
{

    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Add specific attributes
     *
     * @param string $strKey
     * @param mixed  $varValue
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'maxlength':
                if ($varValue > 0)
                {
                    $this->arrAttributes['maxlength'] = $varValue;
                }
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Validate the input and set the value
     */
    public function validate()
    {
        $mandatory = $this->mandatory;
        $options = $this->getPost($this->strName);

        // Check keys only (values can be empty)
        if (\is_array($options))
        {
            foreach ($options as $key=>$option)
            {
                // Unset empty rows
                if ($option['period_start'] == '')
                {
                    unset($options[$key]);
                    continue;
                }

                try
                {
                    $option['period_start'] = strtotime($option['period_start']);
                }
                catch (\InvalidArgumentException $e) {}

                try
                {
                    $option['period_stop'] = strtotime($option['period_stop']);
                }
                catch (\InvalidArgumentException $e) {}

                //$options[$key]['period_number'] = trim($option['period_number']);
                $options[$key]['period_start'] = trim($option['period_start']);
                $options[$key]['period_stop'] = trim($option['period_stop']);


            }
        }

        $options = array_values($options);
        $varInput = $this->validator($options);

        if (!$this->hasErrors())
        {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory)
        {
            $this->mandatory = true;
        }
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string
     */
    public function generate()
    {
        $arrButtons = array('copy', 'delete');

        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || !$this->varValue[0])
        {
            $this->varValue = array(array(''));
        }

        // Begin the table
        $return = '<table id="ctrl_'.$this->strId.'" class="tl_modulewizard">
  <thead>
    <tr>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_period_number'].'</th>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_period_start'].'</th>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_period_stop'].'</th>
      <th></th>
    </tr>
  </thead>
  <tbody class="sortable">';

        // Add fields
        for ($i=0, $c=\count($this->varValue); $i<$c; $i++) {

            $options = '';

            // Add modules
            for ($k = $i+1; $k <= 8; $k++)
            {
                $options .= '<option value="'.$k.'">'.$k.'</option>';
            }

            $lesson = $i + 1;

            if (null !== $this->varValue[$i]['period_start']) {
                $period_start = $this->varValue[$i]['period_start'];
            } else {
                $period_start = time();
            }

            if (null !== $this->varValue[$i]['period_stop']) {
                $period_stop = $this->varValue[$i]['period_stop'];
            } else {
                $period_stop = time();
            }


            $return .= '

    <tr>
      <td><span>'.$lesson.'. Stunde</span></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][period_start]" id="'.$this->strId.'_period_start_'.$i.'" class="tl_text" value="'.date('H:i',$period_start).'"'.$this->getAttributes().'></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][period_stop]" id="'.$this->strId.'_period_stop_'.$i.'" class="tl_text" value="'.date('H:i',$period_stop).'"'.$this->getAttributes().'></td>';

            // Add row buttons
            $return .= '
      <td>';

            foreach ($arrButtons as $button)
            {
                if ($i == ($c - 1)) {
                    if ($button == 'drag') {
                        $return .= ' <button type="button" class="drag-handle" title="' . \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['move']) . '" aria-hidden="true">' . \Image::getHtml('drag.svg') . '</button>';
                    } else {
                        $return .= ' <button type="button" data-command="' . $button . '" title="' . \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['ow_' . $button]) . '">' . \Image::getHtml($button . '.svg') . '</button>';
                    }
                }
            }

            $return .= '</td>
    </tr>';
        }

        return $return.'
  </tbody>
  </table>
  <script>Backend.keyValueWizard("ctrl_'.$this->strId.'")</script>';
    }
}

//class_alias(PeriodWidget::class, 'PeriodWidget');