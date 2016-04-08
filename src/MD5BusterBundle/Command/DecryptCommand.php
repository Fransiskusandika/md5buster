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
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set( 'memory_limit', '25048M' );
        $time = $input->getOption( 'time' );
        if( $time == null ){
            $output->writeln('No time limit specified, defaulting to 60 seconds');
            $time = 60;
        } else {
            if( !is_numeric( $time ) ){
                $output->writeln('Invalid time attribute! Exiting ...');die;
            }
        }
        $timeLimit = new \DateTime('+' . $time . ' seconds');
        $startedAt = new \DateTime();
        $output->writeln('Starting script for ' . $time . ' seconds' );
        $chars = $this->chars;
        $numberOfChars = count( $chars );
        $count = 1;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $offset = $em->getRepository('MD5BusterBundle:MD5Decryption')
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()->getSingleScalarResult()
        ;
        $flushSize = 2500;
        $added = 0;
        for( $i = 1; $i <= $numberOfChars; $i++ ){
            $combinations = $this->sampling( $chars, $i, [] );
            foreach( $combinations as $key => $combination ){
                if( $timeLimit < new \DateTime() ){
                    $em->flush();
                    $this->sendHashReport( $startedAt, new \DateTime(), $added, (memory_get_peak_usage(true)/1024/1024)." MiB" );
                    $output->writeln('Time limit reached! Added ' . $added . ' new entries! Exiting ...');
                    sleep( 5 );
                    die;
                }
                if( $count > $offset ){
                    $hash = md5( $combination );
                    $decryption = new MD5Decryption();
                    $decryption
                        ->setHash( $hash )
                        ->setDecryption( $combination )
                    ;
                    $em->persist( $decryption );
                    $added++;
                    if( $key % $flushSize == 0 ) { // once every %flushSize% iterations
                        $em->flush();
                        $output->writeln( $count . ' - ' . $combination . ' -> ' . (memory_get_peak_usage(true)/1024/1024)." MiB");
                        $em->clear();
                    }
                }
                $count++;
            }
            $em->flush();
            $em->clear();
            unset( $combinations );
        }
    }

    /**
     * @param $chars
     * @param $size
     * @param array $combinations
     * @return array
     */
    private function sampling($chars, $size, $combinations = [])
    {
        # if it's the first iteration, the first set
        # of combinations is the same as the set of characters
        if (empty($combinations)) {
            $combinations = $chars;
        }

        # we're done if we're at size 1
        if ($size == 1) {
            return $combinations;
        }

        # initialise array to put new values in
        $new_combinations = array();

        # loop through existing combinations and character set to create strings
        foreach ($combinations as $combination) {
            foreach ($chars as $char) {
                $new_combinations[] = $combination . $char;
            }
        }

        # call same function again for the next iteration
        return $this->sampling( $chars, $size - 1, $new_combinations );
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
}
