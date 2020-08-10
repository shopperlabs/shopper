<?php

namespace Shopper\Framework\Console;

use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class Thanks
{
    /** @var array<int, string> */
    private const FUNDING_MESSAGES = [
        '',
        '  - Star or contribute to Shopper:',
        '    <options=bold>https://github.com/shopperlabs/framework</>',
        '  - Tweet something about Shopper on Twitter:',
        '    <options=bold>https://twitter.com/laravelshopper</>',
        '  - Sponsor the creator:',
        '    <options=bold>https://github.com/sponsors/mckenziearts</>',
    ];

    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Asks the user to support Pest.
     */
    public function __invoke(): void
    {
        $wantsToSupport = (new SymfonyQuestionHelper())->ask(
            new ArrayInput([]),
            $this->output,
            new ConfirmationQuestion(
                'Can you quickly <options=bold>star our GitHub repository</>? 🙏🏻',
                true,
            )
        );

        if ($wantsToSupport === true) {
            if (PHP_OS_FAMILY == 'Darwin') {
                exec('open https://github.com/shopperlabs/framework');
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec('start https://github.com/shopperlabs/framework');
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec('xdg-open https://github.com/shopperlabs/framework');
            }
        }

        foreach (self::FUNDING_MESSAGES as $message) {
            $this->output->writeln($message);
        }
    }
}