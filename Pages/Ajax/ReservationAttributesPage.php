<?php
/**
 * Copyright 2014 Nick Korbel
 *
 * This file is part of phpScheduleIt.
 *
 * phpScheduleIt is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * phpScheduleIt is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with phpScheduleIt.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationAttributesPresenter.php');

interface IReservationAttributesPage
{
	/**
	 * @return int
	 */
	public function GetRequestedUserId();

	/**
	 * @return int
	 */
	public function GetRequestedReferenceNumber();

	/**
	 * @param Attribute[] $attributes
	 */
	public function SetAttributes($attributes);
}

class ReservationAttributesPage extends Page implements IReservationAttributesPage
{
	/**
	 * @var ReservationAttributesPresenter
	 */
	private $presenter;

	public function __construct()
	{
		parent::__construct();

		$authorizationService = new AuthorizationService(new UserRepository());
		$this->presenter = new ReservationAttributesPresenter($this,
															  new AttributeService(new AttributeRepository()),
															  $authorizationService,
															  new PrivacyFilter(new ReservationAuthorization($authorizationService)),
															  new ReservationViewRepository()
		);
	}

	public function PageLoad()
	{
		$this->presenter->PageLoad(ServiceLocator::GetServer()->GetUserSession());
		$this->Set('ReadOnly', BooleanConverter::ConvertValue($this->GetIsReadOnly()));
		$this->Display('Ajax/reservation/reservation_attributes.tpl');
	}

	/**
	 * @param Attribute[] $attributes
	 */
	public function SetAttributes($attributes)
	{
		$this->Set('Attributes', $attributes);
	}

	/**
	 * @return int
	 */
	public function GetRequestedUserId()
	{
		return $this->GetQuerystring(QueryStringKeys::USER_ID);
	}

	/**
	 * @return string
	 */
	public function GetRequestedReferenceNumber()
	{
		return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
	}

	private function GetIsReadOnly()
	{
		return $this->GetQuerystring(QueryStringKeys::READ_ONLY);
	}
}
