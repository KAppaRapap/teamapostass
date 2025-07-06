<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Generate an SVG avatar with user's initial
     *
     * @param string $name
     * @param int $userId
     * @param int $size
     * @return string
     */
    public static function generateSvgAvatar($name, $userId, $size = 200)
    {
        $initial = strtoupper(substr($name, 0, 1));
        
        // Cores predefinidas para avatares (incluindo cores do tema)
        $colors = [
            '#00FFB2', // Neon Green (cor principal)
            '#FF005C', // Neon Pink (cor secundária)
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
            '#F8C471', '#82E0AA', '#F1948A', '#D7BDE2', '#A3E4D7',
            '#F9E79F', '#7B68EE', '#20B2AA', '#FF7F50', '#32CD32',
            '#FF69B4', '#00CED1', '#FFD700', '#FF4500', '#9370DB'
        ];
        
        // Usar o ID do utilizador para escolher uma cor consistente
        $colorIndex = $userId % count($colors);
        $backgroundColor = $colors[$colorIndex];
        
        // Calcular tamanho da fonte baseado no tamanho do avatar
        $fontSize = $size * 0.4;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="grad' . $userId . '" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:' . $backgroundColor . ';stop-opacity:1" />
                    <stop offset="100%" style="stop-color:' . self::darkenColor($backgroundColor, 20) . ';stop-opacity:1" />
                </linearGradient>
            </defs>
            <circle cx="' . ($size/2) . '" cy="' . ($size/2) . '" r="' . ($size/2) . '" fill="url(#grad' . $userId . ')"/>
            <text x="50%" y="50%" text-anchor="middle" dy="0.35em" font-family="Inter, Arial, sans-serif" font-size="' . $fontSize . '" font-weight="bold" fill="white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">' . $initial . '</text>
        </svg>';
        
        return $svg;
    }
    
    /**
     * Generate a data URL for SVG avatar
     *
     * @param string $name
     * @param int $userId
     * @param int $size
     * @return string
     */
    public static function generateSvgDataUrl($name, $userId, $size = 200)
    {
        $svg = self::generateSvgAvatar($name, $userId, $size);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Darken a hex color by a percentage
     *
     * @param string $hex
     * @param int $percent
     * @return string
     */
    private static function darkenColor($hex, $percent)
    {
        // Remove # if present
        $hex = ltrim($hex, '#');

        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Darken by percentage
        $r = max(0, $r - ($r * $percent / 100));
        $g = max(0, $g - ($g * $percent / 100));
        $b = max(0, $b - ($b * $percent / 100));

        // Convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
