<?php

use Maestroerror\EloquentRegex\Options\ContainSpacesOption;

it('validates input based on space constraints', function () {
    $spaceOption = new ContainSpacesOption();
    
    // Test no spaces allowed
    $spaceOption->noSpaces();
    expect($spaceOption->validate('NoSpacesHere'))->toBeTrue();
    expect($spaceOption->validate('Spaces here'))->toBeFalse();

    // Test no double spaces
    $spaceOption->noSpaces(false)->noDoubleSpaces();
    expect($spaceOption->validate('No  double  spaces   or     more'))->toBeFalse();
    expect($spaceOption->validate('Single spaces only'))->toBeTrue();

    // Test max spaces
    $spaceOption->maxSpaces(3);
    expect($spaceOption->validate('One two three'))->toBeTrue();
    expect($spaceOption->validate('Too many spaces here right?'))->toBeFalse();
});
