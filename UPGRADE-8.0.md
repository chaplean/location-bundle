# How upgrade `v6.0~v7.0` to `v8.0`

* Create a migration containing (DoctrineMigrationsBundle):
```php
<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class VersionXXXXXXXXXXXXXX extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE cl_city ADD code_insee VARCHAR(5) DEFAULT NULL');
        $this->addSql('SELECT "Update cities..."');
    }

    /**
     * @param Schema $schema
     *
     * @return void
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function postUp(Schema $schema)
    {
        /** @var Kernel $kernel */
        $kernel = $this->container->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        // Upgrade cities
        $exitCode = $application->run(new ArrayInput([
            'command' => 'location:upgrade:cities'
        ]));

        $this->abortIf($exitCode !== 0, 'see Exception above ^');
    }

    /**
     * @param Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
```

That's all ! ;)