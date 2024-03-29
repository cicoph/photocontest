<?php
/**
 * AuthAssignmentItemsColumn class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2012-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package auth.widgets
 */

/**
 * Grid column for displaying the authorization items for an assignment row.
 */
class AuthAssignmentItemsColumn extends AuthAssignmentColumn
{
	/**
	 * Initializes the column.
	 */
	public function init()
	{
		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' assignment-items-column';
		else
			$this->htmlOptions['class'] = 'assignment-items-column';
	}

	/**
	 * Renders the data cell content.
	 * @param integer $row the row number (zero-based).
	 * @param mixed $data the data associated with the row.
	 */
	protected function renderDataCellContent($row, $data)
	{
		/* @var $am CAuthManager|AuthBehavior */
		$am = Yii::app()->getAuthManager();
		
		$assignments = $am->getAuthAssignments($data->id);
		$permissions = $am->getItemsPermissions(array_keys($assignments));
		foreach ($permissions as $itemPermission)
		{
			echo $itemPermission['item']->description;
			echo ' <small>'.$this->grid->owner->getItemTypeText($itemPermission['item']->type, false).'</small><br />';
		}
	}
}
