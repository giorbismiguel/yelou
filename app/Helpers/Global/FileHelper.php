<?php
use Illuminate\Http\Request;

/**
 * @param Request $request
 * @param string $nameField
 * @return Request
 */
function get_file(Request $request, string $nameField): Request
{
    if ($request->hasFile($nameField) && $request->file($nameField)->isValid()) {
        // Get image file
        $image = $request->file($nameField);
        $name = strtolower(uniqid($request->input('name') . '_img_', true));
        $fileName = $name . '.' . $image->getClientOriginalExtension();
        $path = $request->file($nameField)->storeAs('photos', $fileName);
        $request->merge([$nameField . '_name' => $path]);
    }

    return $request;
}