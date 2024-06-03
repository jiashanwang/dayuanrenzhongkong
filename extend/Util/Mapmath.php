<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Mapmath
{
	public function is_pt_in_poly($ALon, $ALat, $APoints)
	{
		$iSum = 0;
		if (count($APoints) < 3) {
			return false;
		}
		$iCount = count($APoints);
		for ($i = 0; $i < $iCount; $i++) {
			if ($i == $iCount - 1) {
				$dLon1 = $APoints[$i][0];
				$dLat1 = $APoints[$i][1];
				$dLon2 = $APoints[0][0];
				$dLat2 = $APoints[0][1];
			} else {
				$dLon1 = $APoints[$i][0];
				$dLat1 = $APoints[$i][1];
				$dLon2 = $APoints[$i + 1][0];
				$dLat2 = $APoints[$i + 1][1];
			}
			if ($ALat >= $dLat1 && $ALat < $dLat2 || $ALat >= $dLat2 && $ALat < $dLat1) {
				if (abs($dLat1 - $dLat2) > 0) {
					$dLon = $dLon1 - ($dLon1 - $dLon2) * ($dLat1 - $ALat) / ($dLat1 - $dLat2);
					if ($dLon < $ALon) {
						$iSum++;
					}
				}
			}
		}
		if ($iSum % 2 != 0) {
			return true;
		}
		return false;
	}
}