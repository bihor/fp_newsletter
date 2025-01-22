<?php
declare(strict_types = 1);
namespace Fixpunkt\FpNewsletter\Command;

use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImportFEUsersScheduler extends Command
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this
            ->setDescription('Convert users from tt_address to fe_users')
            ->setHelp('Import newsletter-subscriber from tt_address for Luxletter')
            ->setAliases(['fp_newsletter:importfeusers'])
            ->addArgument(
                'pid',
                InputArgument::OPTIONAL,
                'PID with tt_address-subscribers',
                1
            )
            ->addArgument(
                'group',
                InputArgument::OPTIONAL,
                'ID of the fe_groups',
                1
            )
            ->addArgument(
                'password',
                InputArgument::OPTIONAL,
                'Password for fe_users-subscribers',
                'random'
            );
    }

    /**
     * Executes the command for creating slugs
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int error code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$io->writeln('Initiated and Processing...');
        $pid = intval($input->getArgument('pid'));
        $group = intval($input->getArgument('group'));
        $password = $input->getArgument('password');
        if ($password == 'random') {
            $password = GeneralUtility::makeInstance(Random::class)->generateRandomBytes(20);
        }
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $hashedPassword = $hashInstance->getHashedPassword($password);
        $addresses = [];
        $count = 0;

        // Alle tt_address-Elemente holen
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_address');
        $queryBuilder = $connection->createQueryBuilder();
        if ($pid) {
            $result  = $queryBuilder->select('*')->from('tt_address')->where(
                $queryBuilder->expr()->eq('pid', $pid)
            )->executeQuery()->fetchAllAssociative();
        } else {
            $result = $queryBuilder->select('*')->from('tt_address')->executeQuery()->fetchAllAssociative();
        }
        foreach ($result as $row) {
            $addresses[] = $row;
        }

        // Alle Adressen durchgehen
        foreach ($addresses as $address) {
            if ($address['email']) {
                $queryBuilder = $connection->createQueryBuilder();
                $affectedRows = $queryBuilder
                    ->insert('fe_users')
                    ->values([
                        'name' => (($address['name']) ? trim((string) $address['name']) : ''),
                        'first_name' => (($address['first_name']) ? trim((string) $address['first_name']) : ''),
                        'last_name' => (($address['last_name']) ? trim((string) $address['last_name']) : ''),
                        'email' => $address['email'],
                        'username' => $address['email'],
                        'password' => $hashedPassword,
                        'usergroup' => $group,
                        'description' => 'Imported by fp_newsletter',
                        'pid' => $pid,
                        'tstamp' => $address['tstamp'],
                        'crdate' => ($address['crdate'] ?: $address['tstamp'])
                    ])
                    ->executeStatement();
                $count++;
            }
        }
        $io->success($count.' addresses have been imported from tt_address to fe_users.');
        return 0;
    }
}
