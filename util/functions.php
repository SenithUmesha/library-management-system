<?php

function generateRandomSixDigitNumber()
{
    return mt_rand(100000, 999999);
}

function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}
