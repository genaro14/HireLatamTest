<?php

class SessionSchedulerTest extends \PHPUnit\Framework\TestCase
{
        public function testSessionPageExists()
        {
                $response = $this->getHttpResponseCode('http://localhost/session');
                $this->assertEquals(301, $response);
        }

        private function getHttpResponseCode($url)
        {
                $headers = get_headers($url);
                return intval(substr($headers[0], 9, 3));
        }
}
