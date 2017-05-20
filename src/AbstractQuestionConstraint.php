<?php

namespace Nuntius;

/**
 * Base class for class constraints.
 *
 * If we will take for example the RestartQuestion task we can look at the
 * question questionStartingAgain which could get only yes, no, y, y. In order
 * to validate that we need to replace the prefix question with validate which
 * mean the method will look like validateStartingAgain.
 *
 * validateStartingAgain will get as an argument the value the user inserted and
 * the function need to return a boolean:
 * @code
 *   public function validateStartingAgain($value) {
 *     return in_array($value, ['yes', 'no', 'y', 'n']);
 *   }
 * @endcode
 */
abstract class AbstractQuestionConstraint {

}
