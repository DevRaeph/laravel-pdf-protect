<?php

it('will not use debugging functions')
    ->expect(['dd', 'ds', 'dump', 'ray'])
    ->each->not->toBeUsed();
