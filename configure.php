<?php


$packageSetup = new PackageSetup();

foreach ($packageSetup->getFiles() as $file) {
    echo "Processing file: " . $file . "\n";
}

//echo "Enter the package name:";
//$package_name = trim(fgets(STDIN));
//$packageSetup->setPackageName($package_name);
//$packageSetup->replacePackageName('./README.md');
//
//echo "Package name has been set to: " . $packageSetup->getPackageName() . "\n";


class PackageSetup
{
    private string $packageName;

    public function setPackageName(string $name): void
    {
        $this->packageName = $this->stringToSlug($name);
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function replacePackageName($file): void
    {
        $this->replacePlaceholder($file, '{your-package}', $this->getPackageName());
    }

    public function replacePlaceholder($file, $placeholder, $value): void
    {
        $content = file_get_contents($file);
        $content = str_replace($placeholder, $value, $content);
        file_put_contents($file, $content);
    }

    public function getFiles(): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
        $files = array();

        foreach ($iterator as $file) {
            // Skip if it's not a file
            if (!$file->isFile()) {
                continue;
            }

            // Get the file path
            $filePath = $file->getPathname();

            // Add file path to the array
            $files[] = $filePath;
        }

        return $files;
    }

    public function stringToSlug($string): string
    {
        $slug = str_replace(' ', '-', $string);
        return strtolower($slug);
    }
}