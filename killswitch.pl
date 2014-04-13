use strict;
use warnings;
use DBI;

my $dbh = DBI->connect('dbi:mysql:mark_a2a', 'mark', 'LFtxyfNZpdrKeaBV');
my $set_away_sth = $dbh->prepare('update players set is_away = 1 where last_seen < now() - 15');
my $get_rooms_sth = $dbh->prepare('select r.id from rooms r where not exists(select * from players p where p.room_id = r.id and is_away = 0');
my $kill_sth = $dbh->prepare('delete from rooms where id = ?');

$set_away_sth->execute;
$set_away_sth->finish;

$get_rooms_sth->execute;
while (my $row = $get_rooms_sth->fetchrow_arrayref) {
	print "Killing room " . $row->[0] . ".\n";
	$kill_sth->execute($row->[0]);
}

$get_rooms_sth->finish;
$kill_sth->finish;