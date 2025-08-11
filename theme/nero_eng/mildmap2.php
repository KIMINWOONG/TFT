<div class="lnb2">
  <ul class="lnb_map">
    <?php
    if (isset($submenu[$tNum])) {
        foreach ($submenu[$tNum] as $smenu => $surl) {
            // 메뉴 항목의 bNum
            $menu_bnum = isset($submenu_bNum[$tNum][$smenu]) ? $submenu_bNum[$tNum][$smenu] : null;

            // 현재 페이지의 $bNum과 비교 (같으면 .on)
            $is_current = (isset($bNum) && $menu_bnum == $bNum) ? ' class="on"' : '';

            echo "<li{$is_current}><a href=\"{$surl}\">{$smenu}</a></li>";
        }
    }
    ?>
  </ul>
</div>
