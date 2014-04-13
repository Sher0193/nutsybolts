use strict;
use warnings;

open GREEN_IN, "<", "all_green.txt" or die "Cannot open all_green: $!";
open RED_IN, "<", "all_red.txt" or die "Cannot open all_red: $!";
open GREEN_OUT, ">", "all_green2.txt" or die "Cannot open all_green2: $!";
open RED_OUT, ">", "all_red2.txt" or die "Cannot open all_red2: $!";

while (<RED_IN>) {
	next if /\[Junior/;
	s/\[.*\]//;
	print RED_OUT $_;
}

close RED_IN;
close RED_OUT;

while (<GREEN_IN>) {
	next if /\[Junior/;
	s/\[.*\]//;
	print GREEN_OUT $_;
}

close GREEN_IN;
close GREEN_OUT;