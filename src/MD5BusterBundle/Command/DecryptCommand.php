<?php

namespace MD5BusterBundle\Command;

use MD5BusterBundle\Entity\MD5Decryption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DecryptCommand extends ContainerAwareCommand
{
    private $chars;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->chars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ' ', ',', '.', '!', '?', '-', '+', ':', ';', '\'', '"', '@', '#', '$', '%', '^',
            '&', '*', '(', ')', '=', '_', '`', '~', '/', '\\', '|'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('decrypter:start')
            ->setDescription('start decrypting process')
            ->addOption(
                'time',
                null,
                InputOption::VALUE_REQUIRED,
                'How long should the script run for?'
            )
            ->addOption(
                'lastDecryption',
                null,
                InputOption::VALUE_REQUIRED,
                'what is the last decrypted vaue'
            )
        ;
    }

    /**
     * @return string
     */
    private function getMemoryUsage()
    {
        return (memory_get_peak_usage(true)/1024/1024)."MB";
    }

    /**
     * @param array $charKeys
     * @param $numberOfChars
     * @return bool
     */
    private function isItTheEndOfCombinationLength( $charKeys = [], $numberOfChars )
    {
        $maxKey = $numberOfChars - 1;
        foreach( $charKeys as $charKey ){
            if( $charKey != $maxKey ){
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set( 'memory_limit', '25048M' );
        $time = $input->getOption( 'time' );
        if( $time == null ){
            //$output->writeln('No time limit specified, defaulting to 60 seconds');
            $time = 60;
        } else {
            if( !is_numeric( $time ) ){
                //$output->writeln('Invalid time attribute! Exiting ...');
                die;
            }
        }
        $timeLimit = new \DateTime('+' . $time . ' seconds');
        $startedAt = new \DateTime();
        $chars = $this->chars;
        $numberOfChars = count( $chars );
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $lastDecryption = $input->getOption('lastDecryption');
        if( $lastDecryption == null ){
            $lastDecryption = $em->getRepository('MD5BusterBundle:MD5Decryption')->findOneBy([],['id' => 'DESC'])->getDecryption();
        }
        //$output->writeln('Starting script for ' . $time . ' seconds ' );
        $count = 1;
        $flushSize = 500;
        if( strlen( $lastDecryption ) == 4 ){
            //$output->write('Resuming 4 character long decryption ...');
            $lastDecryptionComponents = str_split($lastDecryption);
            $char1 = array_search( $lastDecryptionComponents[0], $chars );
            $char2 = array_search( $lastDecryptionComponents[1], $chars );
            $char3 = array_search( $lastDecryptionComponents[2], $chars );
            $char4 = array_search( $lastDecryptionComponents[3], $chars );
            if( $this->isItTheEndOfCombinationLength( [
                $char1, $char2, $char3, $char4
            ], $numberOfChars ) ){
                //$output->write('End of line ...');
                $this->createNextLevelCombinationAndSendEOLEmail($startedAt, 4, 'aaaaa' );
                sleep( 5 );
                die;
            } else {
                $char4++; // it's not the end of the line so we move to the next combo to remove duplicates
            }
            for( $i1 = $char1; $i1 < $numberOfChars; $i1++ ){
                for( $i2 = $char2; $i2 < $numberOfChars; $i2++ ){
                    for( $i3 = $char3; $i3 < $numberOfChars; $i3++ ){
                        for( $i4 = $char4; $i4 < $numberOfChars; $i4++ ){
                            if( $timeLimit < new \DateTime() ){
                                //$output->writeln('Time limit reached! Added ' . $count . ' new entries! Exiting ...');
                                $em->flush();
                                $em->clear();
                                $this->sendHashReport( $startedAt, new \DateTime(), $count, $this->getMemoryUsage() );
                                sleep( 3 );
                                die;
                            }
                            $combination = $chars[$i1].$chars[$i2].$chars[$i3].$chars[$i4];
                            $count++;
                            $hash = md5( $combination );
                            $decryption = new MD5Decryption();
                            $decryption
                                ->setHash( $hash )
                                ->setDecryption( $combination )
                            ;
                            $em->persist( $decryption );
                            if( $count % $flushSize == 0 ) { // once every %flushSize% iterations
                                //$output->writeln('Started flushing ' . $flushSize . ' items' . ' -> ' . $this->getMemoryUsage());
                                $em->flush();
                                $em->clear();
                            }
                        }
                        $char4 = 0;
                    }
                    $char3 = 0;
                }
                $char2 = 0;
            }
            //$output->writeln('Finished! Flushing remaining items');
            $em->flush();
            $em->clear();
        } else if( strlen( $lastDecryption ) == 5 ){
            //$output->write('Resuming 5 character long decryption ...');
            $lastDecryptionComponents = str_split($lastDecryption);
            $char1 = array_search( $lastDecryptionComponents[0], $chars );
            $char2 = array_search( $lastDecryptionComponents[1], $chars );
            $char3 = array_search( $lastDecryptionComponents[2], $chars );
            $char4 = array_search( $lastDecryptionComponents[3], $chars );
            $char5 = array_search( $lastDecryptionComponents[4], $chars );
            if( $this->isItTheEndOfCombinationLength( [
                $char1, $char2, $char3, $char4, $char5
            ], $numberOfChars ) ){
                //$output->write('End of line ...');
                $this->createNextLevelCombinationAndSendEOLEmail($startedAt, 5, 'aaaaaa' );
                sleep( 5 );
                die;
            } else {
                $char5++; // it's not the end of the line so we move to the next combo to remove duplicates
            }
            for( $i1 = $char1; $i1 < $numberOfChars; $i1++ ){
                for( $i2 = $char2; $i2 < $numberOfChars; $i2++ ){
                    for( $i3 = $char3; $i3 < $numberOfChars; $i3++ ){
                        for( $i4 = $char4; $i4 < $numberOfChars; $i4++ ){
                            for( $i5 = $char5; $i5 < $numberOfChars; $i5++ ){
                                if( $timeLimit < new \DateTime() ){
                                    //$output->writeln('Time limit reached! Added ' . $count . ' new entries! Exiting ...');
                                    $em->flush();
                                    $this->sendHashReport( $startedAt, new \DateTime(), $count, $this->getMemoryUsage() );
                                    sleep( 3 );
                                    die;
                                }
                                $combination = $chars[$i1].$chars[$i2].$chars[$i3].$chars[$i4].$chars[$i5];
                                $count++;
                                $hash = md5( $combination );
                                $decryption = new MD5Decryption();
                                $decryption
                                    ->setHash( $hash )
                                    ->setDecryption( $combination )
                                ;
                                $em->persist( $decryption );
                                if( $count % $flushSize == 0 ) { // once every %flushSize% iterations
                                    //$output->writeln('Started flushing ' . $flushSize . ' items' . ' -> ' . $this->getMemoryUsage());
                                    $em->flush();
                                    $em->clear();
                                }
                            }
                            $char5 = 0;
                        }
                        $char4 = 0;
                    }
                    $char3 = 0;
                }
                $char2 = 0;
            }
            //$output->writeln('Finished! Flushing remaining items');
            $em->flush();
            $em->clear();
        } else if( strlen( $lastDecryption ) == 6 ){
            //$output->write('Resuming 6 character long decryption ...');
            $lastDecryptionComponents = str_split($lastDecryption);
            $char1 = array_search( $lastDecryptionComponents[0], $chars );
            $char2 = array_search( $lastDecryptionComponents[1], $chars );
            $char3 = array_search( $lastDecryptionComponents[2], $chars );
            $char4 = array_search( $lastDecryptionComponents[3], $chars );
            $char5 = array_search( $lastDecryptionComponents[4], $chars );
            $char6 = array_search( $lastDecryptionComponents[5], $chars );
            if( $this->isItTheEndOfCombinationLength( [
                $char1, $char2, $char3, $char4, $char5, $char6
            ], $numberOfChars ) ){
                //$output->write('End of line ...');
                $this->createNextLevelCombinationAndSendEOLEmail($startedAt, 6, 'aaaaaaa' );
                sleep( 5 );
                die;
            } else {
                $char5++; // it's not the end of the line so we move to the next combo to remove duplicates
            }
            for( $i1 = $char1; $i1 < $numberOfChars; $i1++ ){
                for( $i2 = $char2; $i2 < $numberOfChars; $i2++ ){
                    for( $i3 = $char3; $i3 < $numberOfChars; $i3++ ){
                        for( $i4 = $char4; $i4 < $numberOfChars; $i4++ ){
                            for( $i5 = $char5; $i5 < $numberOfChars; $i5++ ){
                                for( $i6 = $char6; $i6 < $numberOfChars; $i6++ ){
                                    if( $timeLimit < new \DateTime() ){
                                        //$output->writeln('Time limit reached! Added ' . $count . ' new entries! Exiting ...');
                                        $em->flush();
                                        $this->sendHashReport( $startedAt, new \DateTime(), $count, $this->getMemoryUsage() );
                                        sleep( 3 );
                                        die;
                                    }
                                    $combination = $chars[$i1].$chars[$i2].$chars[$i3].$chars[$i4].$chars[$i5].$chars[$i6];
                                    $count++;
                                    $hash = md5( $combination );
                                    $decryption = new MD5Decryption();
                                    $decryption
                                        ->setHash( $hash )
                                        ->setDecryption( $combination )
                                    ;
                                    $em->persist( $decryption );
                                    if( $count % $flushSize == 0 ) { // once every %flushSize% iterations
                                        //$output->writeln('Started flushing ' . $flushSize . ' items' . ' -> ' . $this->getMemoryUsage());
                                        $em->flush();
                                        $em->clear();
                                    }
                                }
                                $char6 = 0;
                            }
                            $char5 = 0;
                        }
                        $char4 = 0;
                    }
                    $char3 = 0;
                }
                $char2 = 0;
            }
            //$output->writeln('Finished! Flushing remaining items');
            $em->flush();
            $em->clear();
        } else {
            //$output->writeln('Not configured to run in this mode');
            $this->sendNotConfiguredHashReport( $startedAt, strlen( $lastDecryption ) );
        }
    }

    /**
     * @param $startedAt
     * @param $currentComLength
     * @param $newCombination
     */
    private function createNextLevelCombinationAndSendEOLEmail( $startedAt, $currentComLength, $newCombination )
    {
        $hash = md5( $newCombination );
        $decryption = new MD5Decryption();
        $decryption
            ->setHash( $hash )
            ->setDecryption( $newCombination )
        ;
        $this->getContainer()->get('doctrine.orm.entity_manager')->persist( $decryption );
        $this->getContainer()->get('doctrine.orm.entity_manager')->flush();
        $this->sendEndOfLineReport( $startedAt, $currentComLength, $newCombination, $this->getMemoryUsage() );
    }

    /**
     * @param $startedAt
     * @param $charLength
     * @throws \Exception
     * @throws \Twig_Error
     */
    private function sendNotConfiguredHashReport( $startedAt, $charLength )
    {
        $email = \Swift_Message::newInstance()
            ->setSubject( 'MD5 Buster Hash Cron Not Configured')
            ->setFrom('no-reply@md5buster.com')
            ->setTo( $this->getContainer()->get('twig')->getGlobals()['owner_email'] )
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    '@MD5Buster/templates/email/hash_cron_report_email.twig',
                    [
                        'startedAt' => $startedAt,
                        'charLength' => $charLength
                    ]
                )
            )
            ->setContentType('text/html')
        ;
        /** @noinspection PhpParamsInspection */
        $this->getContainer()->get('mailer')->send($email);
    }

    /**
     * @param $startedAt
     * @param $endedAt
     * @param $count
     * @param $memory
     * @throws \Exception
     * @throws \Twig_Error
     */
    private function sendHashReport( $startedAt, $endedAt, $count, $memory )
    {
        $email = \Swift_Message::newInstance()
            ->setSubject( 'MD5 Buster Hash Cron Report')
            ->setFrom('no-reply@md5buster.com')
            ->setTo( $this->getContainer()->get('twig')->getGlobals()['owner_email'] )
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    '@MD5Buster/templates/email/hash_cron_report_email.twig',
                    [
                        'startedAt' => $startedAt,
                        'endedAt' => $endedAt,
                        'count' => $count,
                        'memory' => $memory
                    ]
                )
            )
            ->setContentType('text/html')
        ;
        /** @noinspection PhpParamsInspection */
        $this->getContainer()->get('mailer')->send($email);
    }

    /**
     * @param $startedAt
     * @param $combinationLength
     * @param $newCombination
     * @param $memory
     * @throws \Exception
     * @throws \Twig_Error
     */
    private function sendEndOfLineReport( $startedAt, $combinationLength, $newCombination, $memory )
    {
        $email = \Swift_Message::newInstance()
            ->setSubject( 'MD5 Buster Hash Cron End Of Line')
            ->setFrom('no-reply@md5buster.com')
            ->setTo( $this->getContainer()->get('twig')->getGlobals()['owner_email'] )
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    '@MD5Buster/templates/email/hash_cron_eol_report_email.twig',
                    [
                        'startedAt' => $startedAt,
                        'combinationLength' => $combinationLength,
                        'newCombination' => $newCombination,
                        'memory' => $memory
                    ]
                )
            )
            ->setContentType('text/html')
        ;
        /** @noinspection PhpParamsInspection */
        $this->getContainer()->get('mailer')->send($email);
    }
}
