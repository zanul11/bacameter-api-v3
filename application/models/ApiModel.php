<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getData($bt, $idbaca)
	{
		$btfix = substr($bt, 0, 2) . '/' . substr($bt, 2);
		$sql = "SELECT cIdPel, vcNmPel, vcAlamat,vcWilayah,cNoTelp, vcJalan,cKdGol,dTglCatat,dTglUpload, nStLalu, nStIni, nPakai,nPembagi,nPakaiLalu1,nPakaiLalu2,nPakaiLalu3,cKetWM, vcLatitude, vcLongitude,vcLatitudeNew, vcLongitudeNew,lValidLokasi from t_bacameter WHERE cBlth = ? AND cIdPembaca = ? AND cKetWM IS NULL AND txtFoto IS NULL";
		$query = $this->db->query($sql, array($btfix, $idbaca));
		return $query->result_array();
	}

	public function getStatus()
	{
		$sql = "SELECT * FROM m_statuswm";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getTarif()
	{
		$sql = "SELECT * FROM m_tarif";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function doLogin($id, $pass)

	{
		$x = [
			"id_pembaca" => "gagal",
			"sandi_pembaca" => "gagal",
			"nama_pembaca" => "gagal",

		];
		$sql = "SELECT * FROM m_pembaca WHERE id_pembaca=? AND sandi_pembaca=?";
		$query = $this->db->query($sql, array($id, $pass));
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return $x;
		}
	}
	public function updateData($data, $id)
	{
		$cBlth = date('m_Y/');
		$sql = "UPDATE t_bacameter SET nStIni = ?, nPakai = ?, cKetWM = ?, lValidLokasi = ?, vcLatitudeNew = ?, vcLongitudeNew = ?,txtFoto = ?,  dTglCatat = ?, dTglUpload = NOW(), akurasi=?, nStini_=?, lBaca=1 WHERE  cBlth = ? AND cIdPel = ? AND lBaca=0";
		$this->db->trans_begin();

		foreach ($data as $da) {
			$this->db->query($sql, array($da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], $da['vcLatitudeNew'], $da['vcLongitudeNew'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'], $da['akurasi'], $da['nStIni'], $da['cBlth'], $da['cIdPel']));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}

	public function sinkronData($data, $id)
	{
		$cBlth = date('m_Y/');
		$sql = "UPDATE t_bacameter SET nStIni = ?, nPakai = ?, cKetWM = ?, lValidLokasi = ?, vcLatitudeNew = ?, vcLongitudeNew = ?,txtFoto = ?,  dTglCatat = ?, dTglUpload = NOW(), akurasi=?, nStini_=?, lBaca=1 WHERE  cBlth = ? AND cIdPel = ? AND cKetWM!='BACAMETER MANDIRI'";
		$this->db->trans_begin();

		foreach ($data as $da) {
			$this->db->query($sql, array($da['nStIni'], $da['nPakai'], $da['cKetWM'], $da['lValidLokasi'], $da['vcLatitudeNew'], $da['vcLongitudeNew'], base_url('images/') . $cBlth . $id . '/' . $da['txtFoto'], $da['dTglCatat'], $da['akurasi'], $da['nStIni'], $da['cBlth'], $da['cIdPel']));
		}

		$this->db->trans_complete();
		if ($this->db->trans_status())
			return true;
		return false;
	}
}
