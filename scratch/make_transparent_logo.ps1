Add-Type -AssemblyName System.Drawing

$srcPath = "C:\Users\user\.gemini\antigravity-ide\brain\95c7811b-832b-4da3-9980-5066232c9ac1\.user_uploaded\media_1789574264809.jpg"
$outPng = "C:\xampp\htdocs\ChezMoi\public\images\logo.png"

$bmp = [System.Drawing.Bitmap]::FromFile($srcPath)
$newBmp = New-Object System.Drawing.Bitmap($bmp.Width, $bmp.Height, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)

for ($x = 0; $x -lt $bmp.Width; $x++) {
    for ($y = 0; $y -lt $bmp.Height; $y++) {
        $c = $bmp.GetPixel($x, $y)
        # Check if the pixel is dark (part of the logo)
        # The logo is black (#000000 or near black), the checkerboard is #FFFFFF and #C0C0C0 - #CCCCCC
        $brightness = ($c.R * 0.299) + ($c.G * 0.587) + ($c.B * 0.114)
        
        if ($brightness -lt 120) {
            # Logo pixel: keep as black with appropriate alpha
            $alpha = [int]([Math]::Max(0, [Math]::Min(255, (120 - $brightness) * 2.5)))
            if ($brightness -lt 50) {
                $alpha = 255
            }
            $pixel = [System.Drawing.Color]::FromArgb($alpha, 26, 26, 26)
            $newBmp.SetPixel($x, $y, $pixel)
        } else {
            # Transparent
            $newBmp.SetPixel($x, $y, [System.Drawing.Color]::FromArgb(0, 0, 0, 0))
        }
    }
}

$newBmp.Save($outPng, [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Dispose()
$newBmp.Dispose()
Write-Host "Created $outPng successfully!"
