<script type="text/php">
    if (isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 0) {
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 9;
                $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                $y = $pdf->get_height() - 32;
                $x = $pdf->get_width() - 20 -  $fontMetrics->get_text_width($pageText, $font, $size);
                $pdf->text($x, $y, $pageText, $font, $size);
            }
        ');
    }
</script>