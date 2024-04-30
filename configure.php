<?php
$packageSetup = new PackageSetup();

echo "Enter the package name: ";
$package_name = trim(fgets(STDIN));

echo "Enter the package description: ";
$package_description = trim(fgets(STDIN));

$packageSetup->setPackageName($package_name);
$packageSetup->setPackageTitle($package_name);
$packageSetup->setPackageClassName($package_name);
$packageSetup->setPackageDescription($package_description);

$packageSetup->replacePackageName();
$packageSetup->replacePackageTitle();
$packageSetup->replacePackageClassName();
$packageSetup->replacePackageDescription();

echo "Package name has been set to: " . $packageSetup->getPackageName() . "\n";


class PackageSetup
{
    private string $packageName;
    private string $packageTitle;
    private string $packageClassName;
    private string $packageDescription;


    public function setPackageName(string $name): void
    {
        $this->packageName = $this->stringToSlug($name);
    }

    public function setPackageTitle(string $title): void
    {
        $this->packageTitle = $this->stringToTitle($title);
    }

    public function setPackageClassName(string $name): void
    {
        $this->packageClassName = $this->stringToClassName($name);
    }

    public function setPackageDescription(string $description): void
    {
        $this->packageDescription = $description;
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getPackageTitle(): string
    {
        return $this->packageTitle;
    }

    public function getPackageClassName(): string
    {
        return $this->packageClassName;
    }

    public function getPackageDescription(): string
    {
        return $this->packageDescription;
    }

    public function replacePackageName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{your-package}', $this->getPackageName());
        }
    }

    public function replacePackageTitle(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{Your Package}', $this->getPackageTitle());
        }
    }

    public function replacePackageClassName(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{YourPackage}', $this->getPackageClassName());
        }
    }

    public function replacePackageDescription(): void
    {
        foreach ($this->getFiles() as $file) {
            $this->replacePlaceholder($file, '{package description}', $this->getPackageDescription());
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

    function stringToTitle($string): string
    {
        $string = str_replace(array('_', '-'), ' ', $string);
        return ucwords($string);
    }

    function stringToClassName($string): string
    {
        $string = str_replace(array('_', '-'), ' ', $string);
        $title =  ucwords($string);
        return str_replace(' ', '', $title);
    }

}
