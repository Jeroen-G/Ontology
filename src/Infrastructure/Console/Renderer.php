<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure\Console;

use function Termwind\render;

final class Renderer
{
    public function __construct(
        private bool $rawOutput = false
    ) {
    }

    public function title(string $message): void
    {
        $this->render("<div class='p-1 m-1 w-1/3 bg-teal-300 text-center text-teal-900 font-bold'>$message</div>");
    }

    public function success(string $message): void
    {
        $this->render("<div class='p-1 m-1 w-1/3 bg-green-300 text-center text-teal-900 font-bold'>$message</div>");
    }

    public function error(string $title, array $messages): void
    {
        $map = array_map(fn (string $message) => "<dd class='pt-1'>$message</dd>", $messages);
        $list = implode('', $map);

        $html = "
            <div>
                <div class='my-1'>
                    <dl>
                        <dt class='bg-red-500 w-full font-bold p-1 text-white'>$title</dt>
                        $list
                    </dl>
                </div>
            </div>
        ";

        $this->render($html);
    }

    private function render(string $html): void
    {
        if ($this->rawOutput) {
            $html = preg_replace("(class='(.*)')", '', $html);
        }

        render($html);
    }
}
