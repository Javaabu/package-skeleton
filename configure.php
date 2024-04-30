<?php
$packageSetup = new PackageSetup();

echo "Enter the package name:";
$package_name = trim(fgets(STDIN));
$packageSetup->setPackageName($package_name);
$packageSetup->replacePackageName('./README.md');

echo "Package name has been set to: " . $packageSetup->getPackageName() . "\n";


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

    public function replacePackageName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{your-package}', $this->getPackageName());
        }
    }

    public function replacePlaceholder($file, $placeholder, $value): void
    {
        $content = file_get_contents($file);
        $content = str_replace($placeholder, $value, $content);
        file_put_contents($file, $content);
    }

    public function getFiles(): array
    {
        $skipDirectories = array('vendor', '.git', '.idea', 'node_modules');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
        $files = array();

        foreach ($iterator as $file) {
            // Skip if it's not a file
            if (!$file->isFile()) {
                continue;
            }

            // Skip if it's in the directory we want to skip
            $skip = false;
            foreach ($skipDirectories as $skipDirectory) {
                if (str_contains($file->getPathname(), $skipDirectory)) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            if ($file->getFilename() === "configure.php") {
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
